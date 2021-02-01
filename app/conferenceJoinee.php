<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class conferenceJoinee extends Model
{
    //
	protected $fillable = ['user_id','conf_id','visitor'];
}
