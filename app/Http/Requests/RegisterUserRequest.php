<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    protected function prepareForValidation()
    {
      //   まずは「birth_day」を作る
      if($this->old_year && $this->old_month && $this->old_day){
        $this->merge([
          'birth_day' => $this->old_year . '-' . $this->old_month . '-' . $this->old_day,// →「'-'」でハイフン(年-月-日)という意味
        ]);
      }
    }
      // ↑「$this->merge()」でリクエストの中に新しいデータを追加、上書きをするメソッド
      // 「merge」で「birth_day」の中に「$old_year/$old_month/$old_day」を入れ込んでいる
      // controller.phpに記述するときは「$request」だけどFormRequestの時は「$this」で記述する


    public function rules()
    {


      // バリデーションルール
        // ・over_name
        // →必須項目、文字列の型、10文字以下
        // ・under_name
        // →必須項目、文字列の型、10文字以下
        // ・over_name_kana
        // →必須項目、文字列の型、カタカナのみ、30文字以下
        // ・under_name_kana
        // →必須項目、文字列の型、カタカナのみ、30文字以下
        // ・mail_address
        // 必須項目、メールアドレスの形式、登録済みのものは無効、100字以下
        // ・sex
        // →必須項目、男性・女性・その他以外無効
        // ・old_year/old_month/old_day
        // →必須項目、2000年1月1日から今日まで、正しい日付か
        // ・role
        // →必須項目、講師（国語、数学、英語）、生徒以外無効
        // ・password
        // →必須項目、8文字以上30文字以下、確認用と一致しているか

        // ・required:入力必須
        // ・string:文字列
        // ・^[ア-ケー]+$/u:カタカナ
        //   →^：文字列の最初に記述
        //   [ア-ケー]:カタカナのみ
        //   +:1文字以上
        //   $:文字列の最後に記述
        //   /u:UTF-8モード（日本語の正規表現で必須）
        //   ・unique:テーブル名,カラム名
        //   →重複禁止
        // ・in:○○、○○,…
        //   →指定したどれかと一致していればOK
        //   →値はカンマ区切りで記述、文字列でも数値でも可
        //   今回は1・2・3,講師・生徒で判別するからそれを記述
        // ・after_or_equal:2000-01-01:2000年1月1日から
        // ・before_or_equal:today:今日まで
        // ・confirmed
        // →確認用と一致しているか



        return [
          'over_name' => 'required|string|max:10',
          'under_name' => 'required|string|max:10',
          'over_name_kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
          'under_name_kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
          'mail_address' =>'required|email|unique:users,mail_address|max:100',
          'sex' => 'required|in:1,2,3',
          'birth_day'=> 'required|after_or_equal:2000-01-01|before_or_equal:today',// →old_year/old_month/old_dayをまとめて「birth_day」としている
          'role' =>'required|in:1,2,3,4',
          'password' => 'required|min:8|max:30|confirmed',
        ];
      }
          public function messages() {
            return[

              'over_name.required' => '姓は入力必須項目です。',
              'over_name.max'=> '姓は10文字以内で入力してください。',

              'under_name.required'=> '名は入力必須項目です。',
              'under_name.max'=> '名は10文字以内で入力してください。',

              'over_name_kana.required' => 'セイは入力必須項目です。',
              'over_name_kana.regex' => 'セイはカタカナで入力してください。',
              'over_name_kana.max'=> 'セイは30文字以内で入力してください。',

              'under_name_kana.required' => 'メイは入力必須項目です。',
              'under_name_kana.regex' => 'メイはカタカナで入力してください。',
              'under_name_kana.max'=> 'メイは30文字以内で入力してください。',

              'mail_address.required' => 'メールアドレスは入力必須項目です。',
              'mail_address.email' => 'メールアドレスの形式で入力してください。',
              'mail_address.unique' =>'登録済みのメールアドレスは使用できません。',
              'mail_address.max'=> 'メールアドレスは100文字以内で入力してください。',

              'birth_day.required'=> '生年月日は入力必須項目です。',
              'birth_day.after_or_equal'=> '2000年1月1日以降の日付を入力してください。',
              'birth_day.before_or_equal'=> '今日までの生年月日を入力してくだ\さい。',

              'password.required' => 'パスワードは入力必須項目です。',
              'password.min' => '8文字以上で入力してください。',
              'password.max' => '30文字以内で入力してください。',
              'password.confirmed' => '確認用パスワードと一致して下さい。',
            ];
          }
        }
