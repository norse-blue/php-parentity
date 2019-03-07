<?php

namespace NorseBlue\Parentity\Tests\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Tests\Models\Customer;
use NorseBlue\Parentity\Traits\IsMtiChildModel;

/**
 * Class Company
 *
 * @package NorseBlue\Parentity\Tests\Models\Customers
 */
class Company extends Model
{
    use IsMtiChildModel;

    protected $parentModel = Customer::class;

    protected $parentEntity = 'entity';

    protected $fillable = [
        'contact_name',
        'contact_last_name',
        'tax_id',
    ];
}
