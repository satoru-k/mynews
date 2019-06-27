<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    //追記18
    protected $guarded = array('id'); //予期せぬ代入を防止

    public static $rules = array(
      'news_id' => 'required',
      'edited_at' => 'required',
    );
}
