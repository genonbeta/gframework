<?php

namespace genonbeta\view;

use genonbeta\content\PrintableObject;
use genonbeta\util\FlushArgument;

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

    public function onFlush(FlushArgument $flushArguments)
    {
        $output = $this->pattern->getPattern()->onFlush($flushArguments);
        $resultVariables = $this->pattern->onCheckingItems($this->items);

        foreach($resultVariables as $key => $value)
        {
            if($value instanceof PrintableObject)
                $value = $value->onFlush($flushArguments);

            $output = str_replace('{$.'.$key.'}', $value, $output);
        }

        return $output;
    }

    public function flush(FlushArgument $flushArguments)
    {
        return $this->onFlush($flushArguments);
    }
}
