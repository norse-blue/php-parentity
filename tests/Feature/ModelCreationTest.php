<?php

namespace NorseBlue\Parentity\Tests\Feature;

use Illuminate\Database\QueryException;
use NorseBlue\Parentity\Tests\Models\Customer;
use NorseBlue\Parentity\Tests\Models\Customers\Company;
use NorseBlue\Parentity\Tests\Models\Customers\Person;
use NorseBlue\Parentity\Tests\TestCase;

class ModelCreationTest extends TestCase
{
    /** @test */
    public function parent_model_can_be_created_from_own_class()
    {
        $customer = Customer::create(['name' => 'Axel']);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Axel', $customer->name);
        $this->assertDatabaseHas('customers', ['name' => 'Axel']);
    }

    /** @test */
    public function child_model_person_can_be_created_from_parent_class()
    {
        $customer = Customer::create(Person::class, [
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Axel', $customer->name);
        $this->assertDatabaseHas('customers', ['name' => 'Axel']);
        $this->assertInstanceOf(Person::class, $customer->entity);
        $this->assertEquals('Pardemann', $customer->entity->last_name);
        $this->assertDatabaseHas('people', ['last_name' => 'Pardemann']);
        $this->assertDatabaseHas('customers', [
            'entity_type' => get_class($customer->entity),
            'entity_id' => $customer->entity->id,
        ]);
    }

    /** @test */
    public function child_model_company_can_be_created_from_parent_class()
    {
        $customer = Customer::create(Company::class, [
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Norse Blue', $customer->name);
        $this->assertDatabaseHas('customers', ['name' => 'Norse Blue']);
        $this->assertInstanceOf(Company::class, $customer->entity);
        $this->assertEquals('Axel', $customer->entity->contact_name);
        $this->assertEquals('Pardemann', $customer->entity->contact_last_name);
        $this->assertEquals('NB0123456', $customer->entity->tax_id);
        $this->assertDatabaseHas('companies', [
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);
        $this->assertDatabaseHas('customers', [
            'entity_type' => get_class($customer->entity),
            'entity_id' => $customer->entity->id,
        ]);
    }

    /** @test */
    public function child_model_can_be_created_from_parent_class_with_alias_person()
    {
        $customer = Customer::create('person', [
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Axel', $customer->name);
        $this->assertDatabaseHas('customers', ['name' => 'Axel']);
        $this->assertInstanceOf(Person::class, $customer->entity);
        $this->assertEquals('Pardemann', $customer->entity->last_name);
        $this->assertDatabaseHas('people', ['last_name' => 'Pardemann']);
        $this->assertDatabaseHas('customers', [
            'entity_type' => get_class($customer->entity),
            'entity_id' => $customer->entity->id,
        ]);
    }

    /** @test */
    public function child_model_can_be_created_from_parent_class_with_alias_company()
    {
        $customer = Customer::create('company', [
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Norse Blue', $customer->name);
        $this->assertDatabaseHas('customers', ['name' => 'Norse Blue']);
        $this->assertInstanceOf(Company::class, $customer->entity);
        $this->assertEquals('Axel', $customer->entity->contact_name);
        $this->assertEquals('Pardemann', $customer->entity->contact_last_name);
        $this->assertEquals('NB0123456', $customer->entity->tax_id);
        $this->assertDatabaseHas('companies', [
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);
        $this->assertDatabaseHas('customers', [
            'entity_type' => get_class($customer->entity),
            'entity_id' => $customer->entity->id,
        ]);
    }

    /** @test */
    public function exception_thrown_when_creating_parent_and_child_model_person_with_missing_required_fields()
    {
        $this->expectException(QueryException::class);

        $customer = Customer::create(Person::class, [
            'name' => 'Axel',
        ]);
    }

    /** @test */
    public function exception_thrown_when_creating_parent_and_child_model_company_with_missing_required_fields()
    {
        $this->expectException(QueryException::class);

        $customer = Customer::create(Company::class, [
            'name' => 'Axel',
        ]);
    }

    /** @test */
    public function child_model_person_can_be_created_from_own_class()
    {
        $person = Person::create([
            'name' => 'Axel',
            'last_name' => 'Pardemann',
        ]);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals('Pardemann', $person->last_name);
        $this->assertDatabaseHas('people', ['last_name' => 'Pardemann']);
        $this->assertInstanceOf(Customer::class, $person->parent);
        $this->assertEquals('Axel', $person->parent->name);
        $this->assertDatabaseHas('customers', ['name' => 'Axel']);
        $this->assertDatabaseHas('customers', [
            'entity_type' => get_class($person),
            'entity_id' => $person->id,
        ]);
    }

    /** @test */
    public function child_model_company_can_be_created_from_own_class()
    {
        $company = Company::create([
            'name' => 'Norse Blue',
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Axel', $company->contact_name);
        $this->assertEquals('Pardemann', $company->contact_last_name);
        $this->assertEquals('NB0123456', $company->tax_id);
        $this->assertDatabaseHas('companies', [
            'contact_name' => 'Axel',
            'contact_last_name' => 'Pardemann',
            'tax_id' => 'NB0123456',
        ]);
        $this->assertInstanceOf(Customer::class, $company->parent);
        $this->assertEquals('Norse Blue', $company->parent->name);
        $this->assertDatabaseHas('customers', ['name' => 'Norse Blue']);
        $this->assertDatabaseHas('customers', [
            'entity_type' => get_class($company),
            'entity_id' => $company->id,
        ]);
    }
}
