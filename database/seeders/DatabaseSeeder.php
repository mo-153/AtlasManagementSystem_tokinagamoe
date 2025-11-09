<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Register your seeders here
        $this->call([SubjectsTableSeeder::class]);
        $this->call([UsersTableSeeder::class]);
        }
        // ↑SubjectsTableSeeder.php,UsersTableSeeder.phpに記述したコードを実行するように指示出しをしている
        // $this->call([seeder名::class]);

}
