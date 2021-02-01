<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use Notifiable;
    use SoftDeletes;

    const TYPE_WEBINAR = 0 ; 
    const TYPE_VC = 1 ; 

    protected $table = 'session';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'event_id', 'description', 'image', 'date', 'start_time', 'end_time', 'status', 'u_id' , 'type_id'
    ];

    public $appends = ['event_name'];

    public function getEventNameAttribute($value){
        return $this->event->name;
    }

    public function event(){
        return $this->belongsTo('App\Event', 'event_id');
    }

}
