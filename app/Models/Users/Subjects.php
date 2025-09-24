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
         return $this->belongsToMany(User::class);// リレーションの定義
        //  →User::classとは=App/Models/Userになる
        //  →ユーザーと科目のリレーション(多対多)を記述するため
    }
}
