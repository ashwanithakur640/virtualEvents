<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'name', 'title', 'start_date_time', 'end_date_time', 'type', 'amount', 'description', 'status' , 'reschedule_comments' , 'rescheduled'  ,         'organisation_id'
    ];

    const RESCHEDULED = 1;

    const NOT_RESCHEDULED = 0 ;

    public $appends = ['category_name' , 'user_name' ];

   // public $appends = [];

    public function getUserNameAttribute($value){
        return $this->user->first_name;
    }

    public function getCategoryNameAttribute($value){
        return $this->category->name;
    }

    public function User(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    // public function getCategoryNameAttribute($value){
    //     return $this->category->name;
    // }

    //organisation_id

    public function organisation(){
        return $this->hasOne('App\User', 'id', 'organisation_id');
    }

    public function category(){
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function session(){
        return $this->hasMany('App\Session', 'event_id', 'id');
    }

    public function participated_event(){
        return $this->hasMany('App\EventParticipant', 'event_id', 'id');
    }

}
