<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

use App\Models\Users\Subjects;
use App\Models\Users\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
    *
    * @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\RedirectResponse
    *
    * @throws \Illuminate\Validation\ValidationException
    */
    public function store(RegisterUserRequest $request)
    {
        $validated = $request->validated();// バリデーション済みのデータ取得

        // ↓トランザクションは「一連の処理をまとめてすべて成功！orすべて失敗」にする仕組みのこと
        DB::beginTransaction();
        try{
            $user = User::create([
                'over_name' => $validated[ 'over_name'],
                'under_name' => $validated['under_name'],
                'over_name_kana' =>$validated['over_name_kana'],
                'under_name_kana' => $validated['under_name_kana'],
                'mail_address' => $validated['mail_address'],
                'sex' => $validated['sex'],
                'birth_day' => $validated['birth_day'],
                'role' => $validated['role'],
                'password' => bcrypt($validated['password']),
                // ↑bcrypt()でパスワードハッシュ化関数。セキュリティ上必ずハッシュ化して保存すると安全！
                // 「'カラム名'=$validated['値']」
                // →DBのどのカラムにどの値を入れるのか指示をする
            ]);


            // if(in_array($validated['role'],[1,2,3]))
            //     {
            //         $user->subjects()->attach($validated['subject'] ?? []);
            // }
            // ↑
            // ・「in_array(値,配列)」の基本的な形
            //    値:調べたいもの、配列:その中に値があるかどうか
            // ・DBへ保存、併せて多対多の関係(user/subject)を紐づけするから「attach」の記述が必要
            //   $作成したmodelのオブジェクト->modelに記述した多対多のリレーションのメソッド()->attach($validated['中間テーブルへ保存したいもの'] ?? []);



            // ↓元の記述
            // if($request->role == 4){
            //     $user = User::findOrFail($user->id);
            //     $user->subjects()->attach($subjects);
            // }


            DB::commit();
            return view('auth.login.login');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->route('loginView');
        }
    }
}
