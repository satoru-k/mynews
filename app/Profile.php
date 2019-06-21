<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //課題15-5
    protected $guarded = array('id');

    public static $rules = array(
      'name' => 'required',
      'gender' => 'required',
      'hobby' => 'required',
      'introduction' => 'required|max:255',
    );
}
