<?php

namespace NorseBlue\Parentity\Tests\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Tests\Models\Customer;
use NorseBlue\Parentity\Traits\IsMtiChildModel;

/**
 * Class Person
 *
 * @package NorseBlue\Parentity\Tests\Models\Customers
 */
class Person extends Model
{
    use IsMtiChildModel;

    protected $parentModel = Customer::class;

    protected $parentEntity = 'entity';

    protected $fillable = [
        'last_name',
    ];
}
