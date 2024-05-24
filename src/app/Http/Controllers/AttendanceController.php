<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Work;
use App\Models\Breaking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance(Request $request)
    {
        // デフォルトの日付を設定
        $date = $request->query('date');
        $selectedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $selectedDate = Carbon::parse($selectedDate);

        $previousDate = $selectedDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $selectedDate->copy()->addDay()->format('Y-m-d');
        

        // 選択された日付の範囲を定義
        $startOfDay = Carbon::parse($selectedDate)->startOfDay();
        $endOfDay = Carbon::parse($selectedDate)->endOfDay();

        // 選択された日付の勤怠データを取得
        $attendances = Work::whereBetween('start_time',[$startOfDay, $endOfDay])
            ->with('user', 'breakings') // 関連データを読み込み
            ->orderBy('start_time')// スタートタイムが早い順
            ->paginate(5); // ページネーション：1ページあたり5件

        
        // データベースから取得された値をカーボンインスタンスに変換
        $attendances->each(function ($attendance) {
            $attendance->start_time = Carbon::parse($attendance->start_time)->ceilSeconds(10);
            $attendance->end_time = Carbon::parse($attendance->end_time)->ceilSeconds(10);
            $attendance->breakings->each(function ($breaking) {
                $breaking->breaking_start_time = Carbon::parse($breaking->breaking_start_time);
                $breaking->breaking_end_time = Carbon::parse($breaking->breaking_end_time);
            });
        });


        //休憩時間を合計
        $total_break_time = 0;
        foreach ($attendances as $attendance) {
            foreach ($attendance->breakings as $breaking) {
                $break_start = $breaking->breaking_start_time;
                $break_end = $breaking->breaking_end_time;
                // トータル休憩時間
                $total_break_time += $break_start->diffInSeconds($break_end);
            }

            //トータル勤務時間
            $start = $attendance->start_time;
            $end = $attendance->end_time;
            $total_work_time = $start->diffInSeconds($end);
            //実質勤務時間
            $actual_work_time = $total_work_time - $total_break_time;

            //カーボンインスタンスに変換
            $attendance->total_break_time = Carbon::parse($total_break_time)->ceilSeconds(10);
            $attendance->actual_work_time = Carbon::parse($actual_work_time)->ceilSeconds(10);

        }
        

        return view('attendance', [
            'selectedDate' => $selectedDate->format('Y-m-d'),
            'previousDate' => $previousDate,
            'nextDate' => $nextDate,
            'attendances' => $attendances,
        ]);
    }

    // 日付変更用メソッド
    public function changeDate(Request $request, $date)
    {
        return redirect()->route('attendance.attendance', ['date' => $date]);
    }
}