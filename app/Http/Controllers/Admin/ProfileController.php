<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;        //Profileモデルの使用宣言 課題15-5
use App\ProfileHistory; //ProfileHistoryモデルの使用宣言 課題18
use Carbon\Carbon;      //日付操作ライブラリCarbonの使用宣言 課題18

class ProfileController extends Controller
{
  //addアクション
  public function add()
  {
      return view('admin.profile.create');
  }

  //createアクション 以下、課題15-5
  public function create(Request $request)
  {

      //バリデーションを行う
      $this->validate($request, Profile::$rules);
      //newメソッドにより、Profileモデルのインスタンス(空のレコード)$profilesを生成
      $profiles = new Profile;
      //フォームから送信されてきたデータを全て$formに入れる
      $form = $request->all();

      //フォームから画像が送信されてきたら、保存してprofiles->image_pathに画像のパスを保存
      if (isset($form['image'])) {
        $path = $request->file('image')->store('public/image');
        $profiles->image_path = basename($path);
      } else {
        $profiles->image_path = null;
      }

      unset($form['_token']); //フォームから送信されてきた_tokenを削除する
      unset($form['image']);  //フォームから送信されてきたimageを削除する

      //データベースに保存する
      $profiles->fill($form);
      $profiles->save();

      //投稿後、admin/profile/createにリダイレクトする
      return redirect('admin/profile/create');
  }

  //indexアクション 以下、課題17
  public function index(Request $request)
  {
      $cond_name = $request->cond_name;
      //$cond_nameが空白でない場合は、記事を検索して取得する
      if ($cond_name != '') {
          //検索されたら検索結果を取得する
          $posts = Profile::where('name', $cond_name)->get();
      } else {
          //それ以外は全てのデータを取得する
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
  }

  //editアクション 以下、課題17
  public function edit(Request $request)
  {
      //Profileモデルからテーブルのデータを取得し、idを持つものを探して、$profilesに入れる
      $profiles = Profile::find($request->id);
      if (empty($profiles)) {
        abort(404);
      }
      return view('admin.profile.edit', ['profiles_form' => $profiles]);
  }

  //updateアクション 以下、課題17
  public function update(Request $request)
  {
      //バリデーションをかける
      $this->validate($request, Profile::$rules);
      //Profileモデルからテーブルのデータを取得し、idを持つものを探して、$profilesに入れる
      $profiles = Profile::find($request->id);
      //送信されてきたフォームデータを全て$profiles_formに格納する
      $profiles_form = $request->all();

      //画像を変更した時の処理
      if ($request->remove == 'true') {
        $profiles_form['image_path'] = null;
      } elseif ($request->file('image')) {
        $path = $request->file('image')->store('public/image');
        $profiles_form['image_path'] = basename($path);
      } else {
        $profiles_form['image_path'] = $profiles->image_path;
      }

      unset($profiles_form['_token']);
      unset($profiles_form['image']);
      //データベースにremoveカラムがないため、saveする前に$profiles_formから削除する
      unset($profiles_form['remove']);

      //該当するデータを上書きして保存する
      $profiles->fill($profiles_form)->save();

      //以下、課題18
      $history = new ProfileHistory; //モデルから空のインスタンス(レコード)を生成
      $history->profile_id = $profiles->id;
      //Carbonを使って取得した現在時刻を、ProfileHistoryモデルのedited_atとして記録
      $history->edited_at = Carbon::now();
      $history->save();

      //更新が終わったらadmin/profile/editにリダイレクトされる
      return redirect('admin/profile');
  }

  //deleteアクション 以下、課題17
  public function delete(Request $request)
  {
      //Profileモデルからテーブルのデータを取得し、idを持つものを探して、$profilesに入れる
      $profiles = Profile::find($request->id);
      //削除する
      $profiles->delete();

      return redirect('admin/profile');
  }

}
