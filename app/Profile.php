<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //課題15-5
    protected $guarded = array('id'); //予期せぬ代入を防止

    public static $rules = array(
      'name' => 'required',
      'gender' => 'required',
      'hobby' => 'required',
      'introduction' => 'required|max:255',
    );

    //ProfileモデルにProfileHistoryモデルを関連付ける 課題18
    public function histories()
    {
      return $this->hasMany('App\ProfileHistory');
    }
}
