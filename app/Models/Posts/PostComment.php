<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;
use App\Models\Posts\Post;
class PostComment extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];

    public function post(){
        return $this->belongsTo('App\Models\Posts\Post','post_id','id');
    }

    public function user(){
        // return User::where('id', $user_id)->first();
        return $this->belongsTo(User::class,'user_id','id');// ←追記した
    }
}
