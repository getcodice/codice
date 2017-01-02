<?php

namespace Codice\Support\Markdown\Table;

use Webuni\CommonMark\TableExtension\TableExtension;

class Extension extends TableExtension
{
    public function getBlockRenderers()
    {
        $renderers = parent::getBlockRenderers();

        // Override renderer for <table> element
        $renderers['Webuni\\CommonMark\\TableExtension\\Table'] = new Renderer();

        return $renderers;
    }
}
