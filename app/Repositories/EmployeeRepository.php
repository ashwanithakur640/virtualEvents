<?php

namespace App\Repositories;

use App\Employee;
use App\Repositories\BaseRepository;

/**
 * Class BusinessCardRepository
 * @package App\Repositories
 * @version July 24, 2020, 9:02 am UTC
*/

class EmployeeRepository extends BaseRepository
{
    /**
     * @var array
     */

    
    protected $fieldSearchable = [
        'name',
        'description',
        'image',
        'vendor_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Employee::class;
    }
}
