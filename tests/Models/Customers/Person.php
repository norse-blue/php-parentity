<?php

namespace NorseBlue\Parentity\Tests\Models\Customers;

use NorseBlue\Parentity\Eloquent\MtiChildModel;
use NorseBlue\Parentity\Tests\Models\Customer;

class Person extends MtiChildModel
{
    protected $parentModel = Customer::class;

    protected $parentEntity = 'entity';

    protected $fillable = [
        'last_name',
    ];
}
