<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
	use Notifiable;
    use SoftDeletes;

	public $fillable = [
        'user_id', 'session_id', 'rating', 'comment',
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];

    public $appends = ['user_name' , 'user_email'];

    public function getUserNameAttribute($value){
        return $this->user->first_name;
    }

    public function getUserEmailAttribute($value){
        return $this->user->email;
    }

    public function session(){
        return $this->belongsTo('App\Session', 'session_id', 'id');
    } 

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    } 
}
