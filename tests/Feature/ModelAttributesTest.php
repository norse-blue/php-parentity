<?php

namespace NorseBlue\Parentity\Tests\Feature;

use NorseBlue\Parentity\Tests\Models\Customer;
use NorseBlue\Parentity\Tests\Models\Customers\Company;
use NorseBlue\Parentity\Tests\Models\Customers\Person;
use NorseBlue\Parentity\Tests\TestCase;

class ModelAttributesTest extends TestCase
{
    /** @test */
    public function parent_relays_get_attribute_calls_to_child_model_person()
    {
        $customer = Customer::create(Person::class, [
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $this->assertEquals('Axel', $customer->name);
        $this->assertEquals('Pardemann', $customer->last_name);
    }

    /** @test */
    public function parent_relays_set_attribute_calls_to_child_model_person()
    {
        $customer = Customer::create(Person::class, [
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $customer->name = 'New Name';
        $customer->last_name = 'New Last Name';

        $this->assertEquals('New Name', $customer->name);
        $this->assertEquals('New Last Name', $customer->entity->last_name);
    }

    /** @test */
    public function parent_relays_get_attribute_calls_to_child_model_company()
    {
        $customer = Customer::create(Company::class, [
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $this->assertEquals('Norse Blue', $customer->name);
        $this->assertEquals('Axel', $customer->contact_name);
        $this->assertEquals('Pardemann', $customer->contact_last_name);
        $this->assertEquals('NB0123456', $customer->tax_id);
    }

    /** @test */
    public function parent_relays_set_attribute_calls_to_child_model_company()
    {
        $customer = Customer::create(Company::class, [
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $customer->name = 'New Name';
        $customer->contact_name = 'New Contact Name';
        $customer->contact_last_name = 'New Contact Last Name';
        $customer->tax_id = 'New Tax Id';

        $this->assertEquals('New Name', $customer->name);
        $this->assertEquals('New Contact Name', $customer->entity->contact_name);
        $this->assertEquals('New Contact Last Name', $customer->entity->contact_last_name);
        $this->assertEquals('New Tax Id', $customer->entity->tax_id);
    }

    /** @test */
    public function child_model_person_relays_get_attribute_calls_to_parent()
    {
        $person = Person::create([
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $this->assertEquals('Axel', $person->name);
        $this->assertEquals('Pardemann', $person->last_name);
    }

    /** @test */
    public function child_model_person_relays_set_attribute_calls_to_parent()
    {
        $person = Person::create([
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $person->name = 'New Name';
        $person->last_name = 'New Last Name';

        $this->assertEquals('New Name', $person->parent->name);
        $this->assertEquals('New Last Name', $person->last_name);
    }

    /** @test */
    public function child_model_company_relays_get_attribute_calls_to_parent()
    {
        $company = Company::create([
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $this->assertEquals('Norse Blue', $company->name);
        $this->assertEquals('Axel', $company->contact_name);
        $this->assertEquals('Pardemann', $company->contact_last_name);
        $this->assertEquals('NB0123456', $company->tax_id);
    }

    /** @test */
    public function child_model_company_relays_set_attribute_calls_to_parent()
    {
        $company = Company::create([
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $company->name = 'New Name';
        $company->contact_name = 'New Contact Name';
        $company->contact_last_name = 'New Contact Last Name';
        $company->tax_id = 'New Tax Id';

        $this->assertEquals('New Name', $company->name);
        $this->assertEquals('New Contact Name', $company->contact_name);
        $this->assertEquals('New Contact Last Name', $company->contact_last_name);
        $this->assertEquals('New Tax Id', $company->tax_id);
    }
}
