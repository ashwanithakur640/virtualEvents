<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table="faq";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'question', 'answer',
    ];

    public $appends = ['user_email'];

    public function getUserEmailAttribute($value){
        return $this->user->email;
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
