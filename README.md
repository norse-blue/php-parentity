# Parentity

Parentity is a package that allows the use of MTI entities in Laravel 5.7+ using Eloquent models.

## Installation

```bash
composer require "norse-blue/parentity"
```

## Usage

1. Create parent model table with the following fields:

    ```php
    $table->string('entity_type')->nullable();
    $table->unsignedInteger('entity_id')->nullable();
    ```
    *Note: Use the proper id type for your child models (unsignedInteger, unsignedBigInteger, ...).*

1. Create parent model that extends `MtiParentModel`:
    
    ```php
    <?php

    namespace App;

    use NorseBlue\Parentity\Eloquent\MtiParentModel;

    class Customer extends MtiParentModel
    {
        protected $fillable = [
            'name',
        ];

        /** @optional */
        protected $childTypeAliases = [
            'person' => Person::class,
            'company' => Company::class,
        ];

        /** @optional */
        protected $ownAttributes = [
            'id', 'name', 'entity_type', 'entity_id',
        ];
    }
    ```
    *The `$childTypeAliases` array is optional. It allows to create child models using an alias instead of the full qualified class name.*
    *The `$ownAttributes` array is optional. It allows the proxying of get and set calls between parent and child models (instead of `$customer->entity->last_name` it allows to use `$customer->last_name`). It is recommended to specify this array so that everything works smoothly.*

1. Create child model(s) that extend `MtiChildModel`:
    
    ```php
    <?php

    namespace App;

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
    ```
    *Note: All fields are mandatory so that the child model knows how to access the parent model.*

1. Use as every other Eloquent model.
    
    Creating a parent model with specific entity:

    ```php
    $customer = Customer::create(Person::class, [
        'name' => 'Axel',
        'last_name' => 'Pardemann',
    ]);

    /** If configured, you could use $customer->last_name and the call will be proxied to the entity model */
    echo $customer->name . " " . $customer->entity->last_name;

    /**
     * Outputs:
     * Axel Pardemann
     */
    ```

    Creating a child model:
    
    ```php
    $person = Person::create([
        'name' => 'Axel',
        'last_name' => 'Pardemann',
    ]);

    /** If configured, you could use $person->name and the call will be proxied to the parent model */
    echo $person->parent->name . " " . $person->last_name;

    /**
     * Outputs:
     * Axel Pardemann
     */
    ```
