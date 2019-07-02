<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\News;      //Newsモデルを扱えるようになる 追記15
use App\History;   //Historyモデルの使用宣言 追記18
use Carbon\Carbon; //日付操作ライブラリCarbonの使用宣言 追記18
use Storage;       //Storageファサードの使用宣言 追記AWS

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
      //newメソッドにより、Newsモデルのインスタンス(空のレコード)$newsを生成
      $news = new News;
      //フォームから送信されてきたデータを全て$formに入れる
      $form = $request->all();

      //フォームから画像が送信されてきたら、保存して$news->image_pathに画像のパスを保存する
      if (isset($form['image'])) {
        //変更AWS
        $path = Storage::disk('s3')->putFile('/', $form['image'], 'public');
        $news->image_path = Storage::disk('s3')->url($path);
      } else {
        $news->image_path = null;
      }

      unset($form['_token']); //フォームから送信されてきた_tokenを削除する
      unset($form['image']);  //フォームから送信されてきたimageを削除する

      //データベースに保存する
      $news->fill($form);
      $news->save();

      //投稿後、admin/news/createにリダイレクトする 追記14
      return redirect('admin/news/create');
  }

  //indexアクション 以下、追記16
  public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      //$cond_titleが空白でない場合は、記事を検索して取得する
      if ($cond_title != '') {
          //検索されたら検索結果を取得する
          $posts = News::where('title', $cond_title)->orderBy('created_at', 'asc')->get(); //作成日時順
      } else {
          //それ以外は全てのニュースを取得する
          $posts = News::all()->sortBy('created_at'); //作成日時順
      }
      return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }

  //editアクション 以下、追記17
  public function edit(Request $request)
  {
      //Newsモデルからテーブルのデータを取得し、idを持つものを探して、$newsに入れる
      $news = News::find($request->id);
      if (empty($news)) {
        abort(404);
      }
      return view('admin.news.edit', ['news_form' => $news]);
  }

  //updateアクション 以下、追記17
  public function update(Request $request)
  {
      //バリデーションをかける
      $this->validate($request, News::$rules);
      //Newsモデルからテーブルのデータを取得し、idを持つものを探して、$newsに入れる
      $news = News::find($request->id);
      //送信されてきたフォームデータを全て$news_formに格納する
      $news_form = $request->all();

      //画像を変更した時の処理
      if (isset($news_form['image'])) {
        //変更AWS
        $path = Storage::disk('s3')->putFile('/', $news_form['image'], 'public');
        $news->image_path = Storage::disk('s3')->url($path);
      } elseif (isset($request->remove)) {
        $news->image_path = null;
      }

      unset($news_form['_token']);
      unset($news_form['image']);
      //データベースにremoveカラムがないため、saveする前に$news_formから削除する
      unset($news_form['remove']);

      //該当するデータを上書きして保存する
      $news->fill($news_form)->save();

      //以下、追記18
      $history = new History; //newにより、モデルから空のインスタンス(レコード)を生成
      $history->news_id = $news->id;
      //Carbonを使って取得した現在時刻を、Historyモデルのedited_atとして記録
      $history->edited_at = Carbon::now();
      $history->save();

      //更新が終わったらadmin/newsにリダイレクトされる
      return redirect('admin/news');
  }

  //deleteアクション 以下、追記17
  public function delete(Request $request)
  {
      //Newsモデルからテーブルのデータを取得し、idを持つものを探して、$newsに入れる
      $news = News::find($request->id);
      //削除する
      $news->delete();

      return redirect('admin/news');
  }

}
