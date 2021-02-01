<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use Notifiable;
    use SoftDeletes;

    const TYPE_VENDOR_EVENT_CREATION = '0' ;
    const TYPE_CUSTOMER_EVENT_PAYMENT = '1' ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'event_id', 'charge_id', 'amount', 'currency', 'balance_transaction', 'validity','type_id'
    ];
}
