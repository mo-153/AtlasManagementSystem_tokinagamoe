<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory(){
        // リレーションの定義 1対多
        return $this ->belongsTo(mainCategory::class,'main_category_id','id');
    }


    public function posts(){
        // リレーションの定義 多対多
        return $this ->belongsToMany(post::class,'sub_category_id','post_id');
    }// →create_post_sub_categories_table.phpから外部キーの確認して記述


    // リレーションの基本的な書き方
    // public function 関数名(){
    //  return $this ->リレーションの種類(クラス名::class,'外部キー','主のキー');}
    //  →リレーションの種類とは:1対多or多対多
    //  →外部キーとは:相手テーブルと繋げるためのカラム
    //  →主のキーとは:リレーションを組むときに基準となる側のカラム
    //               大体はテーブルのidカラムのこと

}
