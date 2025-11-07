<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];

    public function users(){
         return $this->belongsToMany(User::class,'subject_users','subject_id','user_id');// リレーションの定義
        //  →User::classとは=App/Models/Userになる
        //  →ユーザーと科目のリレーション(多対多)を記述するため
        // belongsToMany(Models名::class,'中間テーブル名','自分の外部キー','相手の外部キー')

    }
}
