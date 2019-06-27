<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileHistory extends Model
{
    //課題18
    protected $guarded = array('id'); //予期せぬ代入を防止

    public static $rules = array(
      'profile_id' => 'required',
      'edited_at' => 'required',
    );
}
