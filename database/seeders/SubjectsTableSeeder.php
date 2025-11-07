<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\Users\Subjects;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 国語、数学、英語を追加
        $subjects = [
            'subject' =>'国語',
            'subject' => '数学',
            'subject' => '英語',
        ];
        // $変数名 = ['該当テーブル内のカラム名' => 'カラムに挿入したい値(文字列)',];
        // →変数名はテーブルに挿入するレコード（行）のリストを保持している
        //  今回はsubjectsテーブルの中にsubjectカラムがあってそのレコードに「国語、数学、英語」がある

        foreach ($subjects as $subject)
        {
            Subject::create($subject);
        }
        //↓foreachループとcreate()内は準備したデータを取り出してDBへ書き込む処理をしている
        //foreach ($ループ処理をする大きな配列名 as $ループ処理するごとに取り出されたレコードを一時的に保存する変数名){
        //該当のテーブルと紐づいているModel名::create($さっき記述した$subject は ['subject' => '国語']などをレコードに書き込むための一時的変数名);}

    }
}
