<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        // リレーションの定義
        return $this ->belongsToMany(subCategories::class,'post_id','sub_category_id');// →create_post_sub_categories_table.phpから外部キーの確認して記述
    }

        // リレーションの基本的な書き方
    // public function 関数名(){
    //  return $this ->リレーションの種類(クラス名::class,'外部キー','主のキー');}
    //  →リレーションの種類とは:1対多or多対多
    //  →外部キーとは:相手テーブルと繋げるためのカラム
    //  →主のキーとは:リレーションを組むときに基準となる側のカラム
    //               大体はテーブルのidカラムのこと



    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }
}
