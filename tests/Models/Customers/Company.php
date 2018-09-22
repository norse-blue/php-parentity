<?php

namespace NorseBlue\Parentity\Tests\Models\Customers;

use NorseBlue\Parentity\Eloquent\MtiChildModel;
use NorseBlue\Parentity\Tests\Models\Customer;

class Company extends MtiChildModel
{
    protected $parentModel = Customer::class;

    protected $parentEntity = 'entity';

    protected $fillable = [
        'contact_name',
        'contact_last_name',
        'tax_id',
    ];
}
