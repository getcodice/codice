<?php

namespace Codice\Support\Markdown\Table;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use Webuni\CommonMark\TableExtension\Table;

class Renderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        /**
         * Practically, the only difference from the original class is setting Bootstrap's attributes for
         * rendered <table> HTML tag.
         */

        if (!($block instanceof Table)) {
            throw new \InvalidArgumentException('Incompatible block type: '.get_class($block));
        }

        $attrs = ['class' => 'table table-bordered'];
        foreach ($block->getData('attributes', []) as $key => $value) {
            $attrs[$key] = $htmlRenderer->escape($value, true);
        }

        $separator = $htmlRenderer->getOption('inner_separator', "\n");

        return new HtmlElement('table', $attrs, $separator.$htmlRenderer->renderBlocks($block->children()).$separator);
    }
}
