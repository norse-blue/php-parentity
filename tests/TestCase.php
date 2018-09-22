<?php

namespace NorseBlue\Parentity\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->runMigrations();
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    public function runMigrations()
    {
        Schema::create('customers', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('entity_type')->nullable();
            $table->unsignedInteger('entity_id')->nullable();
            $table->timestamps();
        });

        Schema::create('people', function ($table) {
            $table->increments('id');
            $table->string('last_name');
            $table->timestamps();
        });

        Schema::create('companies', function ($table) {
            $table->increments('id');
            $table->string('contact_name');
            $table->string('contact_last_name');
            $table->string('tax_id');
            $table->timestamps();
        });
    }
}
