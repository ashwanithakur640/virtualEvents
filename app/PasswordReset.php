<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table="password_resets";

	public $timestamps = false;

    public static function boot(){
        parent::boot();
        static::creating( function ($model) {
	        $model->setCreatedAt($model->freshTimestamp());
	    });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token'
    ];
}
