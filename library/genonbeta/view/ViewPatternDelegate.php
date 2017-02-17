<?php

namespace genonbeta\view;

use genonbeta\controller\Callback;

class ViewPatternDelegate extends ViewPattern
{
    private $pattern;
    private $heldItems = [];
    private $callback;

    public function __construct(ViewSkeleton $skeleton = null, Pattern $pattern, array $notifyingItem, Callback $callback = null)
    {
        $this->pattern = $pattern;
        $this->heldItems = $notifyingItem;
        $this->callback = $callback;

        parent::__construct($skeleton);
    }

    function onCreate()
    {
        return $this->pattern;
    }

	function onNotify()
    {
        return $this->heldItems;
    }

	function onCheckingItems(array $items)
    {
        return $this->callback != null ? $this->callback->onCallback($items) : $items;
    }
}
