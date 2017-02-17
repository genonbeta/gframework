<?php

namespace genonbeta\view;

use genonbeta\content\PrintableObject;
use genonbeta\system\UniversalMessageFilter;
use genonbeta\util\FlushArgument;
use genonbeta\util\PrintableUtils;
use genonbeta\view\PatternFilter;

use Exception;

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
        return self::localFlush($flushArgument, $this->pattern, $this->items);
    }

    protected function localFlush(FlushArgument $flushArgument, ViewPattern $pattern, array $items)
    {
        $output = $pattern->getPattern()->getPlainText();
        $resultVariables = $pattern->onCheckingItems($items);

        foreach($pattern->getItems() as $key => $value)
        {
            $value = $resultVariables[$key]; // only examine items belongs to this pattern (avoid merged ViewPattern items)

            if ($value instanceof ViewSkeleton)
            {
                $value = $value->onHandleViewPattern($key);

                if (!$value instanceof ViewPattern)
                    throw new Exception("ViewSkeleton didn't respond correctly while expecting a ViewPattern instance", 1);
            }

            if ($value instanceof ViewPattern)
            {
                unset($items[$key]); // loop securely
                $value = self::localFlush($flushArgument, $value, $items);
            }

            $value = PrintableUtils::flush($value, $flushArgument);
            $output = str_replace('{$.'.$key.'}', $value, $output);
        }

        $output = UniversalMessageFilter::applyFilter($output, PatternFilter::TYPE_TEMPLATE, $flushArgument);

        return $output;
    }
}
