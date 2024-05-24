<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Work;
use App\Models\Breaking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkController extends Controller
{
     //ボタンが押された時の処理
    public function store(Request $request)
    {
        //ログインしているユーザーid取得
        $user_id = $request->user()->id;
        //現在の時間を取得
        $now = Carbon::now();
        //今日の日付の00:00を取得
        $today = Carbon::now()->startOfDay();

        //最新のworkレコードを取得
        $latestWork = Work::where('user_id', $user_id)
        ->latest()
        ->first();

        //フォームから送信されたアクションを取得
        $action = $request->input('action');
        switch ($action) {
            //勤務開始
            case 'start':
                //最新のレコードがないまたは出勤日が今日より前の処理
                if (is_null($latestWork) || $latestWork->start_time < $today) {

                    //新しいworkレコードを作成
                    $work = new Work();
                    //workレコードにユーザーidを入れる
                    $work->user_id = $user_id;
                    //workモデルのstart_timeに現在時間を入れる
                    $work->start_time = $now;
                    $work->save();
                    //勤怠画面に返す
                    return redirect()->route('home.index')->with('success', '勤務開始しました');
                }

                //最新のレコードが今日より後の処理
                if (($latestWork->start_time >= $today)) {
                    return back()->with('error', 'すでに勤務開始しています');
                }
                break;
                
            
            //勤務終了
            case 'end':
                //レコードがない場合(新規ユーザー)もしくは最新レコードの出勤時間が今日より前の処理
                if (is_null($latestWork) || ($latestWork->start_time < $today)) {
                    return back()->with('error', '勤務開始してください');
                }

                //最新のレコードの出勤時間が今日より後かつ退勤時間がない時の処理
                if (($latestWork->start_time >= $today) && is_null($latestWork->end_time)) {
                    //最新のレコードに退勤時間を入れる
                    $latestWork ->end_time = $now;
                    $latestWork ->save();
                    return redirect()->route('home.index')->with('success', '勤務終了しました');
                }

                //最新のレコードの出勤時間が今日より後かつ退勤時間がある時の処理
                if (($latestWork->start_time >= $today) && !is_null($latestWork->end_time)) {
                    return back()->with('error', 'すでに勤務終了しています');
                }
                break;
            

            //休憩開始
            case 'breaking_start':
                //レコードがない場合(新規ユーザー)もしくは最新レコードが今日より前の時の処理
                if (is_null($latestWork) || ($latestWork->start_time < $today)) {
                    return back()->with('error', '勤務開始してください');
                }

                //最新レコードに出勤時間が今日より後かつ勤務終了してない時の処理
                if (($latestWork->start_time >= $today) && is_null($latestWork->end_time)) {
                    //work_id取得
                    $work_id = $latestWork->id;
                    //最新の休憩記録を取得
                    $latestBreaking = Breaking::where('work_id', $work_id)->latest()->first();
                }

                    //最新の休憩レコードがないもしくは最新の休憩レコードに休憩終了時間がある時
                    if (is_null($latestBreaking) || !is_null($latestBreaking->breaking_end_time)) {
                        //新しい休憩レコードを作成
                        $breaking = new Breaking();
                        //休憩レコードにworkレコードのidを入れる
                        $breaking->work_id = $work_id;
                        //休憩開始時間に現在時間を入れる
                        $breaking->breaking_start_time = $now;
                        $breaking->save();

                        return redirect()->route('home.index')->with('success', '休憩開始しました');
                    }

                    //最新の休憩レコードに休憩終了時間がない時
                    if (is_null($latestBreaking->breaking_end_time)) {
                        return back()->with('error', 'すでに休憩しています');
                    }
                //最新のレコードの出勤時間が今日より後かつ退勤時間がある時の処理
                if (($latestWork->start_time >= $today) && !is_null($latestWork->end_time)) {
                    return back()->with('error', 'すでに勤務終了しています');
                }
                break;
            
            //休憩終了
            case 'breaking_end':
                //レコードがない場合(新規ユーザー)もしくは最新レコードが今日より前の時の処理
                if (is_null($latestWork) || ($latestWork->start_time < $today)) {
                    return back()->with('error', '勤務開始してください');
                }

                //最新レコードに出勤時間が今日より後かつ勤務終了してない時の処理
                if (($latestWork->start_time >= $today) && is_null($latestWork->end_time)) {
                    //work_id取得
                    $work_id = $latestWork->id;
                    //最新の休憩記録を取得
                    $latestBreaking = Breaking::where('work_id', $work_id)->latest()->first();
                }

                    //最新の休憩レコードがないもしくは最新の休憩レコードに休憩終了時間がある時
                    if (is_null($latestBreaking) || !is_null($latestBreaking->breaking_end_time)) {
                        return back()->with('error', '休憩を開始してください');
                    }

                    //最新の休憩レコードに休憩終了時間がない時
                    if (is_null($latestBreaking->breaking_end_time)) {
                        //最新のレコードに退勤時間を入れる
                        $latestBreaking ->breaking_end_time = $now;
                        $latestBreaking ->save();
                        return redirect()->route('home.index')->with('success', '休憩終了しました');
                    }
                
                break;
        }
    }
}

