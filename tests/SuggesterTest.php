<?php

namespace TaylorNetwork\UsernameSuggester\Tests;

use Orchestra\Testbench\TestCase;
use TaylorNetwork\UsernameGenerator\Drivers\NameDriver;
use TaylorNetwork\UsernameSuggester\Drivers\IncrementDriver;
use TaylorNetwork\UsernameSuggester\Facades\UsernameSuggester;
use TaylorNetwork\UsernameSuggester\Suggester;
use TaylorNetwork\UsernameSuggester\Tests\Environment\User;
use TaylorNetwork\UsernameSuggester\UsernameSuggesterProvider;

class SuggesterTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [UsernameSuggesterProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('username_generator.model', User::class);
        $app['config']->set('username_generator.drivers', [NameDriver::class]);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(implode(DIRECTORY_SEPARATOR, [__DIR__, 'Environment', 'migrations']));
//        $this->seed(TestDatabaseSeeder::class);
    }

    public function testBasic()
    {
        $this->assertEquals(collect(['test', 'test0', 'test1']), UsernameSuggester::suggest('test'));
    }

    public function testNoNameProvided()
    {
        $this->assertCount(3, UsernameSuggester::suggest());
    }

    public function testSetAmount()
    {
        $this->assertCount(5, UsernameSuggester::setAmount(5)->suggest());
    }

    public function testNonFacade()
    {
        $suggest = new Suggester();
        $this->assertCount(3, $suggest->suggest());
    }

}
