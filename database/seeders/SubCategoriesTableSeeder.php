<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories =[
            [
                'sub_category' => '国語',
                'main_category_id' =>'1',
                'created_at' => now(),
                'updated_at' =>now(),
            ],
            [
                'sub_category' => '数学',
                'main_category_id' =>'1',
                'created_at' => now(),
                'updated_at' =>now(),
            ],
            [
                'sub_category' => '英語',
                'main_category_id' =>'1',
                'created_at' => now(),
                'updated_at' =>now(),
            ],

        ];
        DB::table('sub_categories')->insert($categories);
    }
}
