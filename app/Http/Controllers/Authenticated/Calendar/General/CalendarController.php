<?php
// スクール予約ページ

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    // 予約をキャンセル
    public function deleteParts(Request $request){
        $reserve_id = $request->reserve_id;
        // reserve_idを取得

        DB::beginTransaction();
        // ↑データの安全性を守るために必要なもの(テンプレート)
        try{
        // ↑テンプレートを使用するときはtry{}で囲むのがルール

            // ↓キャンセルする日の予約枠の確認
            $reserve_user = DB::table('reserve_setting_users')->where('id', $reserve_id)->first();
            // ↑reserve_setting_usersテーブルからキャンセルされたidと'$reserve_idを持ってくる

            if($reserve_user){
                DB::table('reserve_settings')
                ->where('id',$reserve_user ->reserve_setting_id)
                ->increment('limit_users');
                // ↑reserve_settingsテーブルを定義して予約枠を取得、idとさっき定義した$reserve_userの予約枠を取ってくる
                // ↑キャンセルされた予約枠の数を1つ戻す

                DB::table('reserve_setting_users')->where('id',$reserve_id)->delete();
                // ↑予約をキャンセル
            }

            DB::commit();
            }catch(\Exception $e){
                DB::rollback();
            }
            return back();
        }






// 下記コードは元々の記述
        // $user = Auth::user();
        // DBからreserve_setting_usersテーブルにあるid($reserve_id)を取得して削除している
    //     DB::table('reserve_setting_users')
    //     ->where('id',$reserve_id)
    //     ->delete();
    //     return back();
    // }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;// ←カレンダーの日数
            $getDate = $request->getData;// ←カレンダーの日付ごとの予約内容
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
