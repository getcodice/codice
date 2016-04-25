<?php

use Codice\MenuManager;

class MenuManagerTest extends TestCase
{
    /**
     * Test adding one item to the menu and then fetching it.
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
    }

    /**
     * Test adding item with no position set.
     *
     * It should be highest used position + 1.
     *
     * @return void
     */
    public function testAddingItemWithNoPositionSet()
    {
        $manager = new MenuManager;
        $manager->add('about', 'non-existent', 'foo', 3);
        $manager->add('another', 'non-existent', 'foo');

        $actual = $manager->getItems();

        $expected = [
            'about' => [
                'route' => 'about',
                'name' => 'non-existent',
                'icon' => icon('foo'),
                'additionalRoutes' => [],
                'position' => 3,
            ],
            'another' => [
                'route' => 'another',
                'name' => 'non-existent',
                'icon' => icon('foo'),
                'additionalRoutes' => [],
                'position' => 4,
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Items should be sorted by the position.
     *
     * One of the test items has no position set so it should be highest used position + 1.
     *
     * @return void
     */
    public function testSortingItems()
    {
        $manager = new MenuManager;
        $manager->add('third', 'foo', 'foo', 3);
        $manager->add('second', 'foo', 'foo', 2);
        $manager->add('first', 'foo', 'foo', 1);
        $manager->add('fourth', 'foo', 'foo');

        $actual = array_keys($manager->getItems());

        $expected = ['first', 'second', 'third', 'fourth'];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test removing a menu item.
     *
     * @return void
     */
    public function testRemovingItems()
    {
        $manager = new MenuManager;
        $manager->add('third', 'foo', 'foo', 3);
        $manager->add('second', 'foo', 'foo', 2);
        $manager->add('first', 'foo', 'foo', 1);
        $manager->add('fourth', 'foo', 'foo');

        $manager->remove('third');

        $actual = array_keys($manager->getItems());

        $expected = ['first', 'second', 'fourth'];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test adding two items with duplicated route, former should be overridden.
     *
     * @return void
     */
    public function testDuplicatedItems()
    {
        $manager = new MenuManager;
        $manager->add('foo', 'invalid', 'icon');
        $manager->add('foo', 'valid', 'icon');

        $actual = $manager->getItems();

        $expected = [
            'foo' => [
                'route' => 'foo',
                'name' => 'valid',
                'icon' => icon('icon'),
                'additionalRoutes' => [],
                'position' => 1,
            ],
        ];

        $this->assertEquals($expected, $actual);
    }
}
