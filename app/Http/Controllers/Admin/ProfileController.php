<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile; //課題15-5

class ProfileController extends Controller
{
  public function add()
  {
      return view('admin.profile.create');
  }

  //以下、課題15-5
  public function create(Request $request)
  {
      //バリデーションを行う
      $this->validate($request, Profile::$rules);

      $profiles = new Profile;
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

      return redirect('admin/profile/create');
  }

  public function edit()
  {
      return view('admin.profile.edit');
  }

  public function update()
  {
      return redirect('admin/profile/edit');
  }

}
