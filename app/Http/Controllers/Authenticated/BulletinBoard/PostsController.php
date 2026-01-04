<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;

use App\Http\Requests\BulletinBoard\PostFormRequest;

class PostsController extends Controller
{


    // 検索部分
    public function show(Request $request){
        $keyword = '%'.$request->keyword.'%';
        $posts = Post::with('user')->withCount('postComments')
        ->withCount('likes')
        ->get();
        // ↑withCount('likes')の記述もれ

        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        $baseQuery=Post::with('user')->withCount('postComments')
        ->withCount('likes');
        // ↑$baseQuery=Post::with('user')->withCount('postComments')の記述意味
        // $baseQuery:データベースの呼び出し
        // Post::with():どのテーブル(モデル)かを指定し関連データも一緒取得
        // withCount():関連データの件数を数える
        // 'postComments': Post.phpで定義しているpublic function postComments()のリレーションメソッド名を記述している
        // withCount('リレーション名');


        // キーワード検索
        if(!empty($request->keyword)){
            $posts = $baseQuery// →$baseQueryは検索の土台
            // Post::with('user', 'postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')
            ->orWhereHas('user', function ($query) use ($keyword) {
                $query->where('over_name', 'like', $keyword)
                ->orWhere('under_name','like',$keyword);
            })
            // ↑orWhereの次は「orWhereHas」で記述を続ける
            // ↑orWhereHas('user', function ($query) use ($keyword)
            // user：Postモデルにあるpublic function user() のメソッド名
            // function ($query):匿名関数
            // ($keyword):変数名
            ->get();
        }
        // ↑検索時キーワード入力後post_title,postに含まれる投稿を表示

        if($request->category_word){
            $sub_category = $request->category_word;
            $posts = $baseQuery
            // ->where('sub_category','like','%'.$request->category_word.'%');
            // Post::with('user', 'postComments')
            ->where('sub_category', $category_word)//←完全一致:where('カラム名',値)
            ->get();
        }
        // ↑検索時カテゴリーワードを入力してsub_categoryに含まれているワードを表示

        if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = $baseQuery
            // Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }
        // ↑ログインユーザーがいいねをした投稿を表示

        if($request->my_posts){
            $posts = $baseQuery
            // Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        // ↑ログインユーザーの投稿を表示

        if (!$request->keyword && !$request->category_word && !$request->my_posts){
            $posts=$baseQuery->get();
        }
        // ↑キーワード検索でpost_title,postに当てはまるもの、カテゴリー検索でsub_categoryに当てはまるもの、いいねした投稿、自分の投稿を表示させる

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }






    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
                'user_id' => Auth::id(),
                'post_title' => $request->post_title,
                'post' => $request->post_body,
            ]);

            // post_sub_categoriesに保存する↓（中間テーブル）
            $post->subCategories()->attach($request->post_category_id);

            return redirect()->route('post.show');
        }


    public function postEdit(Request $request){
        $validated = $request->validate([
            'post_title' =>'required|string|max:100',
            'post_body' => 'required|string|max:2000',
        ],
        [
            'post_title.required'=>'タイトルは必ず入力してください。',
            'post_title.max'=>'タイトルは100字以内で入力してください。',

            'post_body.required' => '投稿内容は必ず入力してください',
            'post_body.max' => '投稿内容は2000文字以内で入力してください',
        ]);

        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }



    public function mainCategoryCreate(Request $request){
        $request->validate([
        'main_category_name' => 'required|string|max:100|unique:main_categories,main_category', // main_categoriesテーブルのmain_categoryカラムで一意性をチェック
        ],[
        'main_category_name.required' => 'メインカテゴリーは入力必須です。',
        'main_category_name.max'      => '100文字以内で入力してください。',
        'main_category_name.unique'   => 'そのメインカテゴリー名は既に登録されています。',
    ]);

        MainCategory::create([
            'main_category' => $request->main_category_name,
        ]);
        //  return redirect()->route('post.input')->withInput();
        return redirect()->back();

        $main_categories = MainCategory::with('subCategories')->get();
        $sub_categories = SubCategory::get();
        return view('authenticated.bullentinboard.post_create',compact('main_categories','sub_categories'));
    }



        public function subCategoryCreate(Request $request){
        $request->validate([
        'main_category_id' => 'required|exists:main_categories,id',
        'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category', // main_categoriesテーブルのmain_categoryカラムで一意性をチェック
    ], [
        'main_category_id.required' => 'メインカテゴリーを選択してください。',
        'main_category_id.exists'   => '選択したメインカテゴリーは存在しません。',
        'sub_category_name.required'     => 'サブカテゴリーは入力必須です。',
        'sub_category_name.max'          => '100文字以内で入力してください。',
        'sub_category_name.unique'       => 'そのサブカテゴリー名は既に登録されています。',
    ]);
        SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' => $request->sub_category_name,
        ]);
        // return redirect()->route('post.input')->withInput();
        return redirect()->back();

        $main_categories = MainCategory::with('subCategories')->get();
        $sub_categories = subCategory::get();
        return view('authenticated.bullentinboard.post_create',compact('main_categories','sub_categories'));
    }
    public function commentCreate(Request $request){

        // バリデーションルール
        // ・comment
        // →必須項目、250文字、文字列型

        $validated = $request->validate([
            'comment' => 'required|max:250|string',
        ],
        [
            'comment.required' =>'コメントは入力必須です。',
            'comment.max' => '250文字以内で入力してください。',
        ]);


        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        // ↑myBulletinBoardメソッド「ログインユーザーの投稿一覧」
        $posts = Auth::user()->posts()->withCount('likes')->get();
        // ↑ログインユーザーの全ての投稿を取得して、投稿のいいねをカウントを取得
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }




    public function likeBulletinBoard(){
        // ↑likeBulletinBoardメソッド「ログインユーザーのいいねした投稿一覧」

        // $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        // ↑Like::with('users')でLikeレコードを取得するときに「user」を一緒に取得
        // whereで「条件で絞り込む」
        // →Likeテーブルのlike_user_idに一致するログインユーザーを取得

        $like_post_id = Like::where('like_user_id', Auth::id())->pluck('like_post_id')->toArray();


        $posts = Post::with('user')->whereIn('id', $like_post_id)->withCount('likes')->get();
        // whereIn('テーブルのカラム名',カラム名の中から$○○に一致するもの)
        // whereInは「複数条件の絞り込み」

        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        // $like = new Like;

        // $like->like_user_id = $user_id;
        // $like->like_post_id = $post_id;
        // $like->save();
        $like=Like::firstOrCreate([
            'like_user_id'=>$user_id,'like_post_id'=>$post_id
        ]);
        // ↑firstOrCreateは「いいねの重複を防ぐ」もの
        // user_idでどのユーザーがpost_idでどの投稿にいいねをしたのか確認しして、いいねが重複しないようにしている

        $post=Post::withCount('likes')->find($post_id);
        // ↑withCount('リレーション')で「リレーションの件数を取得」
        // find($引数)でデータベースから引数のレコードを取得
        // =>データベースからpost_idを取得、その投稿についているいいねの総数を取得

        $likesCount=$post->likes_count;
        return response()->json(['likesCount'=>$likesCount]);
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        // $like = new Like;

        // $like->where('like_user_id', $user_id)
        // ->where('like_post_id', $post_id)
        // ->delete();

        $deleted=Like::where('like_user_id',$user_id)
        ->where('like_post_id',$post_id)
        ->delete();
        // ↑データベースに「like_user_id が$user_idかつlike_post_idが$post_id のレコードを見つけて削除する」という記述

        $post=Post::withCount('likes')->find($post_id);
        // ↑withCount('リレーション')で「リレーションの件数を取得」
        // find($引数)でデータベースから引数のレコードを取得
        // =>データベースからpost_idを取得、その投稿についているいいねの総数を取得

        $likesCount=$post->likes_count;
        return response()->json(['likesCount'=>$likesCount]);
    }
}
