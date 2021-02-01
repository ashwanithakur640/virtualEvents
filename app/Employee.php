<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class employee
 * @package App\Models
 * @version July 24, 2020, 9:02 am UTC
 *
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $vendor_id
 */
class Employee extends Model
{
    use SoftDeletes;

    public $table = 'employee';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const CAN_CHAT = 1;
    const CANNOT_CHAT = 0;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'email',
        'phone',
        'country_code',
        'vendor_id',
        'user_id',
        'can_chat'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'country_code' => 'string',
        'vendor_id' => 'integer',
        'user_id' => 'integer',
        'can_chat' => 'integer' //to check if employee can chat or not 
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        //'image' => 'required'
    ];


    public function user()
    {
        return $this->belongsTo('App\User', 'vendor_id', 'id');
    }    
    
}
