<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class conferenceMessage extends Model
{
    //
	protected $fillable = ['conf_id','messages','status'];
}
