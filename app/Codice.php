<?php

namespace Codice;

use Codice\Plugins\Filter;

class Codice
{
    private $version = '0.6.0-dev';

    public function getVersion()
    {
        /**
         * Filters Codice version returned by the core.
         *
         * @since 0.4
         *
         * @return string
         */
        return Filter::call('core.version', $this->version);
    }
}
