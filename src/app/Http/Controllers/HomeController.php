<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Work;
use App\Models\Breaking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        //ログインしているユーザー情報取得
        $user_id = auth()->id();
        //現在の時間を取得
        $now = Carbon::now();
        //今日の日付の00:00を取得
        $today = Carbon::now()->startOfDay();

        // 今日の最新の勤務を取得
        $latestWork = Work::where('user_id', $user_id)
                          ->where('start_time', '>=', $today)
                          ->latest('start_time')
                          ->first();

        // ボタンの無効化フラグ
        $canStartShift = true;  // 勤務開始ボタン
        $canEndShift = true;    // 勤務終了ボタン
        $canStartBreak = true;  // 休憩開始ボタン
        $canEndBreak = true;    // 休憩終了ボタン

        // 今日の勤務がない場合
        if (is_null($latestWork)) {
            $canStartShift = true;
            $canEndShift = false;
            $canStartBreak = false;
            $canEndBreak = false;
        } else {

        //最新の休憩を取得
        $latestBreaking = Breaking::where('work_id', $latestWork->id)
                        ->latest('breaking_start_time')
                        ->first();

            // 2. 勤務中、休憩がまだ開始されていないか、休憩が終了している場合
            if (is_null($latestBreaking) || !is_null($latestBreaking->breaking_end_time)) {
                $canStartShift = false;
                $canEndShift = true;
                $canStartBreak = true;
                $canEndBreak = false;
            }

            // 3. 休憩中の場合
            if (!is_null($latestBreaking) && is_null($latestBreaking->breaking_end_time)) {
                $canStartShift = false;
                $canEndShift = false;
                $canStartBreak = false;
                $canEndBreak = true;
            }

            // 4. 今日の勤務が終了している場合
            if (!is_null($latestWork->end_time)) {
                $canStartShift = false;
                $canEndShift = false;
                $canStartBreak = false;
                $canEndBreak = false;
            }
        }



        return view('home', [
            'canStartShift' => $canStartShift,
            'canEndShift' => $canEndShift,
            'canStartBreak' => $canStartBreak,
            'canEndBreak' => $canEndBreak,
        ]);
    }
}