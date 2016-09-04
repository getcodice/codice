<?php

use Codice\Plugins\Filter;

class PluginFiltersTest extends TestCase
{
    public function testSingleFilter()
    {
        Filter::register('test.hook', 'test_filter', function($value) {
            return $value;
        });

        $output = Filter::call('test.hook', 'test');

        $this->assertEquals('test', $output);
    }

    public function testMultipleFilters()
    {
        Filter::register('test.hook1', 'first_filter', function($value) {
            return str_replace('test', 'hello', $value);
        }, 1);

        Filter::register('test.hook1', 'second_filter', function($value) {
            return $value = $value . ', world!';
        }, 2);

        Filter::register('test.hook1', 'third_filter', function($value) {
            return strtoupper($value);
        }, 3);

        $output = Filter::call('test.hook1', 'test');

        $this->assertEquals('HELLO, WORLD!', $output);
    }

    public function testSortingFilters()
    {
        Filter::register('test.hook2', 'second_filter', function($value) {
            return 'foobar';
        }, 4);

        Filter::register('test.hook2', 'first_filter', function($value) {
            return strtolower($value);
        }, 1);

        $output = Filter::call('test.hook2', 'test');

        $this->assertEquals('foobar', $output);
    }

    public function testDuplicatedFilterNames()
    {
        Filter::register('test.hook4', 'filter', function($value) {
            return strtolower($value);
        });

        Filter::register('test.hook4', 'filter', function($value) {
            return strrev($value);
        });

        $output = Filter::call('test.hook4', 'tEsT');

        $this->assertEquals('TsEt', $output);
    }

    public function testFilterNameUniquenessPerHook()
    {
        Filter::register('test.hook5', 'filter', function($value) {
            return strtolower($value);
        });

        Filter::register('test.hook6', 'filter', function($value) {
            return strtoupper($value);
        });

        $firstOutput = Filter::call('test.hook5', 'tEsT');
        $secondOutput = Filter::call('test.hook6', 'tEsT');

        $this->assertEquals('test', $firstOutput);
        $this->assertEquals('TEST', $secondOutput);
    }

    public function testCallingHookWithNoFilters()
    {
        $output = Filter::call('test.hook7', 'test value');

        $this->assertEquals('test value', $output);
    }

    public function testDeregisteringFilters()
    {
        Filter::register('test.hook8', 'first_filter', function($value) {
            return strrev($value);
        });

        Filter::register('test.hook8', 'second_filter', function($value) {
            return strtoupper($value);
        });

        Filter::deregister('test.hook8', 'second_filter');

        $output = Filter::call('test.hook8', 'test');

        $this->assertEquals('tset', $output);
    }

    public function testFiltersWithParameters()
    {
        Filter::register('test.hook9', 'first_filter', function($value, $parameters) {
            return $value . $parameters['one'];
        });

        $output = Filter::call('test.hook9', 'test', [
            'one' => 'ABC',
        ]);

        $this->assertEquals('testABC', $output);
    }
}
