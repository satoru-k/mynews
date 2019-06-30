<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\News; //Newsモデルの使用宣言 追記19
use App\Profile; //Profileモデルの使用宣言 課題19-1

class NewsController extends Controller
{
    //indexアクション 以下、追記19
    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        //$cond_titleが空白でない場合は、記事を検索して取得する
        if ($cond_title != '') {
            //検索されたら、検索結果をupdated_atで降順に並べ換えてから取得する
            $posts = News::where('title', $cond_title)->orderBy('updated_at', 'desc')->get();
        } else {
            //全てのnewsテーブルを取得し、updated_atで降順に並べ換える
            $posts = News::all()->sortByDesc('updated_at');
        }

        //もし投稿された記事が1つ以上あれば、
        if (count($posts) > 0) {
            //一番最新の記事だけ$postsから抜き出して、$headlineに入れる
            $headline = $posts->shift();
        } else {
            $headline = null;
        }

        /* Viewテンプレートにnews/index.blade.phpファイルと、
           headline、posts、cond_titleという変数を渡す */
        return view('news.index', ['headline' => $headline, 'posts' => $posts, 'cond_title' => $cond_title]);
    }

    //profileアクション 以下、課題19-1
    public function profile(Request $request)
    {
        //全てのprofilesテーブルを取得し、updated_atで降順に並べ換える
        $profiles = Profile::orderBy('updated_at', 'desc')->get();

        /* Viewテンプレートにnews/profile.blade.phpファイルと、
           profilesという変数を渡す */
        return view('news.profile', ['profiles' => $profiles]);
    }
}
