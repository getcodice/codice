<?php

use Codice\MenuManager;

class MenuManagerTest extends TestCase
{
    /**
     * Test adding one item to the menu.
     *
     * @return void
     */
    public function testAddingItem()
    {
        $manager = new MenuManager;
        $manager->add('about', 'non-existent', 'foo', 1);

        $actual = $manager->getItems();

        $expected = [];
        $expected['about'] = [
            'route' => 'about',
            'name' => 'non-existent',
            'icon' => icon('foo'),
            'additionalRoutes' => [],
            'position' => 1,
        ];

        $this->assertEquals($expected, $actual);
        //var_dump($actual);
    }
}
