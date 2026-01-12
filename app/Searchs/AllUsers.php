<?php
namespace App\Searchs;

use App\Models\Users\User;

class AllUsers implements DisplayUsers{

  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $query = User::with('subjects');
    // ↑withでまとめて情報を取ってくる
    if(!is_null($subjects)){
      // ↑科目を選択しているときにだけ(!is_nullで空の逆の意味になる)
      $query->whereHas('subjects',function($query) use ($subjects){
        // ↑whereHas:持っているデータだけを絞り込む
      $query->whereIn('subjects_id',$subjects);
      // ↑whereIn:指定したリスト（配列）の中の、どれか一つにでも当てはまるかチェックする
      // ↑subjects_idはsubjectsテーブルにあるidカラムのこと
    });

    }
    return $query->get();
  }


}
