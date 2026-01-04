<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories\MainCategory;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 外部キーの整合性チェック機能を無効化する
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

      $category_content =MainCategory::where('main_category','教科')->first();
    //   まずmain_categoryから教科を取得
    if($category_content){
        $categories_content =$category_content->id;
        DB::table('sub_categories')->truncate();
        $categories =[
            [
                'sub_category' => '国語',
                'main_category_id' =>$categories_content,
                'created_at' => now(),
                'updated_at' =>now(),
            ],
            [
                'sub_category' => '数学',
                'main_category_id' =>$categories_content,
                'created_at' => now(),
                'updated_at' =>now(),
            ],
            [
                'sub_category' => '英語',
                'main_category_id' =>$categories_content,
                'created_at' => now(),
                'updated_at' =>now(),
            ],
        ];
        DB::table('sub_categories')->insert($categories);
     }
    //  外部キーの整合性チェック機能を**有効化（ON）**に戻す。
     DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
