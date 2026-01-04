<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    public function subCategories(){
        // リレーションの定義 1対多の関係
        return $this ->hasMany(SubCategory::class,'main_category_id','id');
    }

    public function posts(){
        return $this ->hasMany(post::class,'main_category_id','id');
    }

    // リレーションの基本的な書き方
    // public function 関数名(){
    //  return $this ->リレーションの種類(クラス名::class,'外部キー','主のキー');}
    //  →リレーションの種類とは:1対多or多対多
    //  →外部キーとは:相手テーブルと繋げるためのカラム
    //  →主のキーとは:リレーションを組むときに基準となる側のカラム
    //               大体はテーブルのidカラムのこと

    // 1対多の関係の時
    // →1側(親テーブル)のリレーション
    // public function 子テーブルの複数形(){
    //  return $this ->hasMany(子クラス名::class,'外部キー','主のキー');}
    //  →外部キー:子テーブルにある「親テーブルを表すカラム」
    //   多側(親テーブル)には外部キーがないためここは多側で記述するときも子テーブルにある外部キーを記述する
    //  →主のキー:親テーブルの主のキー(たいていidを記述)


}
