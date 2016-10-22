<?php

use Codice\Codice;
use Codice\Plugins\Filter;

class CodiceTest extends TestCase
{
    private $codice;

    public function setUp()
    {
        parent::setUp();

        $this->codice = new Codice;

        $reflection = new ReflectionClass($this->codice);
        $reflectionVersion = $reflection->getProperty('version');
        $reflectionVersion->setAccessible(true);
        $reflectionVersion->setValue($this->codice, 'test-version');
    }

    public function testGettingCodiceVersion()
    {
        $this->assertEquals('test-version', $this->codice->getVersion());
    }
}
