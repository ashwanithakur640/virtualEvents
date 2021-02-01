<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class attendedConference extends Model
{
    //
    protected $fillable = ['conference_id','user_id','view' , 'left_at'];

    public $appends = ['session_name' ,'event_name' , 'customer_name' ,'customer_email' ];

    public function getCustomerNameAttribute($value){
        return isset( $this->user  ) ? $this->user->first_name : '' ;
    }

    public function getSessionNameAttribute($value){
        return isset( $this->session  ) ? $this->session->name : '' ;
    }

	public function getCustomerEmailAttribute($value){
        return isset( $this->user  ) ? $this->user->email : '' ;
    }

    public function getEventNameAttribute($value){
        return isset($this->session->event) ? $this->session->event->title : '';
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function session(){
        return $this->belongsTo('App\Session', 'conference_id');
    }

}
