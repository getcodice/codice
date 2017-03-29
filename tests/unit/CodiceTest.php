<?php

use Codice\Codice;
use Codice\Plugins\Filter;

class CodiceTest extends TestCase
{
    private $codice;

    public function testGettingCodiceVersion()
    {
        $this->setCodiceVersion('test-version');

        $this->assertEquals('test-version', $this->codice->getVersion());
    }

    public function testIsVersionStable()
    {
        $this->setCodiceVersion('1.0.0');
        $this->assertTrue($this->codice->isVersionStable());

        $this->setCodiceVersion('0.5.0-dev');
        $this->assertFalse($this->codice->isVersionStable());
    }

    public function testVersionFilter()
    {
        $this->setCodiceVersion('test-version');

        Filter::register('core.version', 'test_filter', function ($value) {
            return strtoupper($value);
        });

        $this->assertEquals('TEST-VERSION', $this->codice->getVersion());
    }

    private function setCodiceVersion($version)
    {
        $this->codice = new Codice;

        $reflection = new ReflectionClass($this->codice);
        $reflectionVersion = $reflection->getProperty('version');
        $reflectionVersion->setAccessible(true);
        $reflectionVersion->setValue($this->codice, $version);
    }
}
