<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionPresenter extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'session_presenter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'session_id' 
    ];


    // public function getEventNameAttribute($value){
    //     return $this->event->name;
    // }

    public $appends = ['session_name'];

    public function getSessionNameAttribute($value){
        return isset($this->session) ? $this->session->name : '';
    }

    public function session(){
        return $this->belongsTo('App\Session', 'session_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
