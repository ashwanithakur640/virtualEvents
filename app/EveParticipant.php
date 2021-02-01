<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EveParticipant extends Model
{
    use Notifiable;
    //use SoftDeletes;

    // const TYPE_WEBINAR = 0 ; 
    // const TYPE_VC = 1 ; 

    protected $table = 'eventparticipant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'event_id' , 'document'
    ];


    // public function getEventNameAttribute($value){
    //     return $this->event->name;
    // }

    public $appends = ['event_name'];

    public function getEventNameAttribute($value){
        return $this->event->name;
    }

    public function event(){
        return $this->belongsTo('App\Event', 'event_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
