<?php

namespace genonbeta\view;

use genonbeta\content\PrintableObject;
use genonbeta\system\UniversalMessageFilter;
use genonbeta\util\FlushArgument;
use genonbeta\util\PrintableUtils;
use genonbeta\view\PatternFilter;

class FlushableViewPattern implements ViewInterface
{
    private $pattern;
    private $items;

    public function __construct(ViewPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function onCreate(array $items = [])
    {
        $this->items = $items;
        return $this;
    }

    public function onFlush(FlushArgument $flushArgument)
    {
        $output = $this->pattern->getPattern()->getPlainText();
        $resultVariables = $this->pattern->onCheckingItems($this->items);

        foreach($resultVariables as $key => $value)
        {
            $value = PrintableUtils::flush($value, $flushArgument);
            $output = str_replace('{$.'.$key.'}', $value, $output);
        }

        $output = UniversalMessageFilter::applyFilter($output, PatternFilter::TYPE_TEMPLATE, $flushArgument);

        return $output;
    }
}
