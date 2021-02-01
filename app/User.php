<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    const ROLE_SUPERADMIN = '0' ;
    const ROLE_VENDOR = '1' ;
    const ROLE_PARTICIPANTS = '2' ;
    const ROLE_END_USER = '3' ;
    const ROLE_EMPLOYEE = '4' ;

    const PAY_OFFLINE = 0;
    const PAY_ONLINE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'email_verified_at',      'password','country_code', 'country_iso', 'mobile', 'company_name', 'company_city_location', 'state', 'country', 'address', 'website', 'office_no', 'office_email_id', 'image', 'status', 'role_id', 'deleted_is_admin', 'verify_token', 'vendor_id' , 'can_pay_offline' ,
            'salutation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $appends = ['full_name', 'full_phone_number' , 'vendor_name'];

    public function getFullNameAttribute($value){
        return $this->first_name. ' ' .$this->middle_name. ' ' .$this->last_name;
    }

    public function getFullPhoneNumberAttribute($value){
        return $this->country_code. ' ' .$this->mobile;
    }

    public function getVendorNameAttribute($value){
        
        return isset( $this->user ) ? $this->user->first_name. ' ' .$this->user->middle_name. ' ' .$this->user->last_name : ''; 

    }

    public function vendor_details(){
        return $this->hasOne('App\VendorDetail', 'user_id', 'id');
    }

     public function user(){
        return $this->hasOne('App\User', 'vendor_id', 'id');
    }

}
