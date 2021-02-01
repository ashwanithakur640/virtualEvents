<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Payment;
class EventParticipant extends Model
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'user_id', 'ip'
    ];

    public $appends = ['customer_name' ,'customer_email' , 'event_name' , 'event_cost' , 'event_vendor' , 'transaction_id'];

    public function getCustomerNameAttribute($value){
        return isset($this->user) ? $this->user->first_name : '' ;
    }

    public function getCustomerEmailAttribute($value){
        return isset($this->user) ? $this->user->email : '' ;
    }

    public function getEventNameAttribute($value){
        return isset($this->event) ? $this->event->name : '';
    }

    public function getEventCostAttribute($value){
        return isset($this->event) ? $this->event->amount : '' ;
    }

    public function getEventVendorAttribute($value){
        return isset($this->event) ? $this->event->user_name : '' ;
    }


    public function getTransactionIdAttribute($value){


        if(isset($this->event)){
            if( $this->event->amount != 0  ){

                $transaction_id = Payment::where('event_id', $this->event_id )->where(      'user_id' , $this->user_id )->first();

                if(!empty($transaction_id)){

                    return $transaction_id->balance_transaction;

                }else{
                    return '' ;
                }

            }else{
                return '' ;
            }
        }
        return '' ;
    }


    public function event(){
        return $this->belongsTo('App\Event', 'event_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    
}
