# Parentity

Parentity is a package that allows the use of MTI (Multi-Table Inheritance) entities in Laravel 5.7+ using Eloquent models.

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

1. Create parent model that extends `Models` and uses `IsMtiParentModel`trait:
    
    ```php
    <?php

    namespace App;

    use App\Customers\Company;
    use App\Customers\Person;
    use Illuminate\Database\Eloquent\Model;
    use NorseBlue\Parentity\Traits\IsMtiParentModel;

    class Customer extends Model
    {
        use IsMtiParentModel;

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
            'id',
            'name',
            'entity_type',
            'entity_id',
        ];
    }
    ```
    
    **Notes:**
    
    - The `$childTypeAliases` array is optional. It allows to create child models using an alias instead of the full qualified class name.
    - The `$ownAttributes` array is optional. It allows the proxying of get and set calls between parent and child models (instead of `$customer->entity->last_name` it allows to use `$customer->last_name`). It is recommended to specify this array so that everything works neatly.

1. Create the child model(s) that extends `Models` and uses `IsMtiChildModel` trait:
    
    ```php
    <?php

    namespace App;

    use App\Customer;
    use Illuminate\Database\Eloquent\Model;
    use NorseBlue\Parentity\Traits\IsMtiChildModel;

    class Person extends Model
    {
        use IsMtiChildModel;
        
        protected $parentModel = Customer::class;

        protected $parentEntity = 'entity';

        protected $fillable = [
            'last_name',
        ];
    }
    ```
    
    **Notes:**
    
    - All fields are mandatory so that the child model knows how to access the parent model.

## Model creation
    
### Creating a model from the parent class
    
To create a model from the parent class you need to specify the entity type before the attributes.

```php
$customer = Customer::create(Person::class, [
    'name' => 'Axel',
    'last_name' => 'Pardemann',
]);
```

### Creating a model from the child class

The best way to create a model is from the child classes. Just include all the models (parent and child) attributes.

```php
$person = Person::create([
    'name' => 'Axel',
    'last_name' => 'Pardemann',
]);
```

In both cases a parent and a linked child will be created with the given attribute values, which will be stored in each model's table.


## Model properties

There are two ways that we can access the model properties: explicitly or implicitly.

### Explicit properties

When using explicit properties we _explicitly_ ask for the parent or the entity property:

```
// using the previously created models

// Explicit property from the parent model
echo $customer->name . " " . $customer->entity->last_name;

// Explicit property from the child model
echo $person->parent->name . " " . $person->last_name;
```

Outputs:
```
Axel Pardemann
Axel Pardemann
```

### Implicit properties

If set up correctly, instead of using the explicit property calls we can use the shorter version which implicitly proxies the call to the parent or child model:

```
// using the previously created models

// Implicit property from the parent model
echo $customer->name . " " . $customer->last_name;

// Implicit property from the child model
echo $person->name . " " . $person->last_name;
```

Outputs:
```
Axel Pardemann
Axel Pardemann
```

## Code status and known issues

- This package is still a proof of concept. At the moment we can only create models and use the properties.
- It is planned to extend the functionality to the `make`, `update` and `save` methods first.

# Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
