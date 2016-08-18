<?php

use Codice\Plugins\Action;

class PluginActionsTest extends TestCase
{
    public function testSingleAction()
    {
        Action::register('test.hook', 'test_action', function() {
            echo 'test';
        });

        $output = $this->getCallOutput('test.hook');

        $this->assertEquals('test', $output);
    }

    public function testSortingActions()
    {
        Action::register('test.hook2', 'second_action', function() {
            echo 'bar';
        }, 4);

        Action::register('test.hook2', 'first_action', function() {
            echo 'foo';
        }, 1);

        $output = $this->getCallOutput('test.hook2');

        $this->assertEquals('foobar', $output);
    }

    public function testSortingWithSamePriority()
    {
        Action::register('test.hook3', 'first_action', function() {
            echo '1';
        });

        Action::register('test.hook3', 'second_action', function() {
            echo '2';
        });

        $output = $this->getCallOutput('test.hook3');

        $this->assertEquals('12', $output);
    }

    public function testDuplicatedActionNames()
    {
        Action::register('test.hook4', 'action', function() {
            echo '1';
        });

        Action::register('test.hook4', 'action', function() {
            echo '2';
        });

        $output = $this->getCallOutput('test.hook4');

        $this->assertEquals('2', $output);
    }

    public function testActionNameUniquenessPerHook()
    {
        Action::register('test.hook5', 'action', function() {
            echo 'foo';
        });

        Action::register('test.hook6', 'action', function() {
            echo 'bar';
        });

        $firstOutput = $this->getCallOutput('test.hook5');
        $secondOutput = $this->getCallOutput('test.hook6');

        $this->assertEquals('foo', $firstOutput);
        $this->assertEquals('bar', $secondOutput);
    }

    public function testCallingHookWithNoActions()
    {
        $output = $this->getCallOutput('test.hook7');

        $this->assertEquals('', $output);
    }

    public function testDeregisteringActions()
    {
        Action::register('test.hook8', 'first_action', function() {
            echo 'foo';
        });

        Action::register('test.hook8', 'second_action', function() {
            echo 'bar';
        });

        Action::deregister('test.hook8', 'second_action');

        $output = $this->getCallOutput('test.hook8');

        $this->assertEquals('foo', $output);
    }

    public function testActionsWithParameters()
    {
        Action::register('test.hook9', 'first_action', function($parameters) {
            echo $parameters['one'];
        });

        $output = $this->getCallOutput('test.hook9', [
            'one' => 'foo',
        ]);

        $this->assertEquals('foo', $output);
    }

    private function getCallOutput($hook, array $parameters = [])
    {
        ob_start();
        Action::call($hook, $parameters);
        return ob_get_clean();
    }
}
