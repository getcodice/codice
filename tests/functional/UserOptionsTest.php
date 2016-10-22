<?php

use Codice\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserOptionsTest extends TestCase
{
    use DatabaseMigrations;

    private $testOptions = [
        'age' => 33,
        'enabled' => true,
        'foo' => 'bar',
        'language' => 'en',
        'phone' => '',
    ];

    public function testUserOptions()
    {
        // Create test user
        $user = factory(User::class)->create(['options' => $this->testOptions]);

        // Read some options ensuring types are valid
        $this->assertEquals(33, $user->options['age']);
        $this->assertEquals(true, $user->options['enabled']);
        $this->assertEquals('bar', $user->options['foo']);
        $this->assertEquals('', $user->options['phone']);

        // Try to save user options
        $newOptions = $user->options;
        $newOptions['age'] = 45;
        $newOptions['foo'] = 'test';
        $newOptions['phone'] = '123';

        $user->options = $newOptions;

        // Read again to check value is valid
        $this->assertEquals(45, $user->options['age']);
        $this->assertEquals('test', $user->options['foo']);
        $this->assertEquals('123', $user->options['phone']);
    }
}
