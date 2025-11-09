<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [

            // ↓仮でデータを定義する必要がある
            // レコードごとに[]で分けて記述する

            // 教師(国語)sex:男性(1)、role:国語(1)
            [
            'over_name' => '山田',
            'under_name' => '太郎',
            'over_name_kana' => 'ヤマダ',
            'under_name_kana' => 'タロウ',
            'mail_address' => 'tarou@outlook.jp',
            'sex' => 1,
            'birth_day' => '2000-01-01',
            'role' => 1,
            'password' => Hash::make('tarou000001'),            // ←パスワードはハッシュ化する
            'created_at' => now(),
            'updated_at' => now(),
            ],


            // 教師(数学)sex:女性(2)、role:数学(2)
            [
            'over_name' => '山田',
            'under_name' => '花子',
            'over_name_kana' => 'ヤマダ',
            'under_name_kana' => 'ハナコ',
            'mail_address' => 'hanako@outlook.jp',
            'sex' => 2,
            'birth_day' => '2000-02-01',
            'role' => 2,
            'password' => Hash::make('hanako000002'),            // ←パスワードはハッシュ化する
            'created_at' => now(),
            'updated_at' => now(),
            ],


             // 教師(英語)sex:男性(1)、role:英語(3)
            [
            'over_name' => '山田',
            'under_name' => '一郎',
            'over_name_kana' => 'ヤマダ',
            'under_name_kana' => 'イチロウ',
            'mail_address' => 'ichiro@outlook.jp',
            'sex' => 1,
            'birth_day' => '2000-03-01',
            'role' => 3,
            'password' => Hash::make('ichiro000003'),            // ←パスワードはハッシュ化する
            'created_at' => now(),
            'updated_at' => now(),
            ],


            // 生徒sex:その他(3)、role:生徒(4)
            [
             'over_name' => '山田',
            'under_name' => '次郎',
            'over_name_kana' => 'ヤマダ',
            'under_name_kana' => 'ジロウ',
            'mail_address' => 'jiro@outlook.jp',
            'sex' => 3,
            'birth_day' => '2000-04-01',
            'role' => 4,
            'password' => Hash::make('jiro000004'),            // ←パスワードはハッシュ化する
            'created_at' => now(),
            'updated_at' => now(),
            ],
           ];

 DB::table('users')->insert($users);

}
}
