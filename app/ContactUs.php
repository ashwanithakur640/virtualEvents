<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'contact_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name', 'middle_name', 'last_name', 'email', 'country_code', 'country_iso', 'mobile', 'description', 'answer',
    ];

    public $appends = ['full_name', 'full_phone_number'];

    public function getFullNameAttribute($value){
        return $this->first_name. ' ' .$this->middle_name. ' ' .$this->last_name;
    }

    public function getFullPhoneNumberAttribute($value){
        return $this->country_code. ' ' .$this->mobile;
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
