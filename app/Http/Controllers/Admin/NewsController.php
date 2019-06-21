<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\News; //News Modelを扱えるようになる 追記15

class NewsController extends Controller
{
  //addアクション
  public function add()
  {
      return view('admin.news.create');
  }

  //createアクション 追記14
  public function create(Request $request)
  {
      //以下、追記15
      //バリデーションを行う
      $this->validate($request, News::$rules);

      $news = new News;
      $form = $request->all();

      //フォームから画像が送信されてきたら、保存して$news->image_pathに画像のパスを保存する
      if (isset($form['image'])) {
        $path = $request->file('image')->store('public/image');
        $news->image_path = basename($path);
      } else {
        $news->image_path = null;
      }

      unset($form['_token']); //フォームから送信されてきた_tokenを削除する
      unset($form['image']);  //フォームから送信されてきたimageを削除する

      //データベースに保存する
      $news->fill($form);
      $news->save();

      //追記14
      return redirect('admin/news/create'); //admin/news/createにリダイレクトする
  }

}
