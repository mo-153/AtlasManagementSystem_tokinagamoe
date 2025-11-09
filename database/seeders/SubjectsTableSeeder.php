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
            ['subject' =>'国語'],
            ['subject' => '数学'],
            ['subject' => '英語'],
        ];
        // $変数名 = ['該当テーブル内のカラム名' => 'カラムに挿入したい値(文字列)',];
        // →変数名はテーブルに挿入するレコード（行）のリストを保持している
        //  今回はsubjectsテーブルの中にsubjectカラムがあってそのレコードに「国語、数学、英語」がある
        // DBのレコードごと(1行ごと)で[]で分ける必要がある


        DB::table('subjects')->insert($subjects);
        // ↑DB内にあるsubjectsテーブルにデータを入れるための記述
        // ($subjects)になるのは上で$subjects = [['subject' =>'国語'],['subject' => '数学'],['subject' => '英語'],];を記述しているから

    }
}
