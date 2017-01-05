<?php

namespace genonbeta\view;

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

    public function onFlush(array $flushArguments)
    {
        $output = $this->pattern->getPattern();
        $resultVariables = $this->pattern->onCheckingItems($this->items);

        foreach($resultVariables as $key => $value)
        {
            if($value instanceof PrintableObject)
                $value = $value->onFlush($flushArguments);

            $output = str_replace('{$.'.$key.'}', $value, $output);
        }

        return $output;
    }

    public function flush(array $flushArguments)
    {
        return $this->onFlush($flushArguments);
    }
}
