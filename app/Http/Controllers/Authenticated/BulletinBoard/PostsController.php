<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;

class PostsController extends Controller
{

    public function store(Request $request){

        // バリデーションルール
        // ・post_category_id
        // →必須項目、登録されているサブカテゴリか
        // ・post_title
        // →必須項目、文字列型、最大100文字
        // ・post_body
        // →必須項目、文字列型、最大2000文字


        $validated = $request->validate([
            'post_category_id' => 'required|string',
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',
        ],

        // バリデーションメッセージ
        [
            'post_category_id.required' => 'カテゴリーは必ず入力してください。',

            'post_title.required' => 'タイトルは必ず入力してください',
            'post_title.max' => 'タイトルは100文字以内で入力してください',

            'post_body.required' => '投稿内容は必ず入力してください',
            'post_body.max' => '投稿内容は2000文字以内で入力してください',
        ]);
    }





    public function show(Request $request){
        $posts = Post::with('user')->withCount('postComments')->get();
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        $baseQuery=Post::with('user')->withCount('postComments');
        // ↑$baseQuery=Post::with('user')->withCount('postComments')の記述意味
        // $baseQuery:データベースの呼び出し
        // Post::with():どのテーブル(モデル)かを指定し関連データも一緒取得
        // withCount():関連データの件数を数える
        // 'postComments': Post.phpで定義しているpublic function postComments()のリレーションメソッド名を記述している

        if(!empty($request->keyword)){
            $posts = $baseQuery
            // Post::with('user', 'postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = $baseQuery->get();
            // Post::with('user', 'postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = $baseQuery
            // Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = $baseQuery
            // Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        if (!$request->keyword && !$request->category_word && !$request->my_posts){
            $posts=$baseQuery->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
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
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
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
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
