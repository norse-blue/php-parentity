<?php

namespace NorseBlue\Parentity\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use NorseBlue\Parentity\Tests\Models\Customers\Company;
use NorseBlue\Parentity\Tests\Models\Customers\Person;
use NorseBlue\Parentity\Traits\IsMtiParentModel;

/**
 * Class Customer
 *
 * @package NorseBlue\Parentity\Tests\Models
 */
class Customer extends Model
{
    use IsMtiParentModel;

    protected $fillable = [
        'name',
    ];

    protected $childTypeAliases = [
        'person' => Person::class,
        'company' => Company::class,
    ];

    protected $ownAttributes = [
        'id',
        'name',
        'entity_type',
        'entity_id',
    ];
}
