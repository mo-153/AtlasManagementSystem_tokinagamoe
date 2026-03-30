<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNameDetails implements DisplayUsers{

  // 改修課題：選択科目の検索機能
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    if(is_null($gender)){
      $gender = ['1', '2', '3'];
    }else{
      $gender = array($gender);
    }
    if(is_null($role)){
      $role = ['1', '2', '3', '4'];
    }else{
      $role = array($role);
    }
    $users = User::with('subjects')
    ->where(function($q) use ($keyword){
      $q->Where('over_name', 'like', '%'.$keyword.'%')
      ->orWhere('under_name', 'like', '%'.$keyword.'%')
      ->orWhere('over_name_kana', 'like', '%'.$keyword.'%')
      ->orWhere('under_name_kana', 'like', '%'.$keyword.'%');
    })
    ->where(function($q) use ($role, $gender){
      $q->whereIn('sex', $gender)
      ->whereIn('role', $role);
    });

    // ↓元記述
    // whereInだと複数検索の時に1つでも当てはまるユーザーをすべて表示されてしまう
    // ->whereHas('subjects', function($q) use ($subjects){
    //   $q->where('subjects.id', $subjects);
    // })

    if($subjects){
      foreach($subjects as $subject_id){
        $users->whereHas('subjects',function($q) use ($subject_id){
          $q->where('subjects.id', $subject_id);
        });
      }
    }
    // ↑foreachとwhereHasを一緒に記述してAND検索(且つ)を実装。
    //  複数検索するときにすべてに当てはまるユーザーを表示させる
    $searchResults = $users->orderBy('over_name_kana', $updown)->get();
    return $searchResults;
  }
}


// ☆where句
// where:「且つ」/A且つB
// whereHas:リレーション先(中間テーブル)のテーブルで絞り込み
//          DBではなくリレーション先から髭右得したいデータを持ってくる

// whereIn:「どれか」/A,B,Cのどれか
// orWhere:「または」/どちらか1つ(AまたはB)
