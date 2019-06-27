<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //追記15
    protected $guarded = array('id');

    public static $rules = array(
      'title' => 'required',
      'body' => 'required',
    );

    //NewsモデルにHistoryモデルを関連付ける 追記18
    public function histories()
    {
      return $this->hasMany('App\History');
    }
}
