<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Like;
use App\Models\Posts\PostComment;
class Post extends Model
{
    // const UPDATED_AT = null;
    // const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
        'sub_category_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User','user_id','id');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment','post_id','id');
    }

    public function mainCategory(){
        return $this -> belongsTo(MainCategory::class,'main_category_id','id');
    }
    public function SubCategories(){
        // リレーションの定義 多対多
        return $this ->belongsToMany(SubCategory::class,'post_sub_categories','post_id','sub_category_id');
        // →create_post_sub_categories_table.phpから外部キーの確認して記述
    }

        // 多対多のリレーションの基本的な書き方
        // public function 関数名(){
        // return $this->belongsToMany(相手モデル::class, '中間テーブル名', '自分の外部キー', '相手の外部キー');
        // }
        // →外部キーとは:相手テーブルと繋げるためのカラム
        // →主のキーとは:リレーションを組むときに基準となる側のカラム
        //              大体はテーブルのidカラムのこと



    // コメント数
    public function PostCounts(){
        // return Post::with('postComments')->find($post_id)->postComments();
        return $this->hasMany(PostCount::class,'post_id');
    }

    // いいね数
    public function likes(){
        return $this->hasMany(Like::class,'like_post_id','id');
    }
}
