<?php

namespace NorseBlue\Parentity\Tests\Models;

use NorseBlue\Parentity\Eloquent\MtiParentModel;
use NorseBlue\Parentity\Tests\Models\Customers\Company;
use NorseBlue\Parentity\Tests\Models\Customers\Person;

class Customer extends MtiParentModel
{
    protected $fillable = [
        'name',
    ];

    protected $childAliases = [
        'person' => Person::class,
        'company' => Company::class,
    ];
}
