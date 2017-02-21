<?php

namespace genonbeta\content;

use Exception;

use genonbeta\util\HashMap;

class StaticView
{
    private $viewClass;
    private $itemList;

    public function __construct($className)
    {
        if (!class_exists($className))
            throw new Exception($className." class doesn't exist", 1);

        $this->viewClass = $className;
        $this->itemList = new HashMap();
    }

    public function getItemList()
    {
        return $this->itemList;
    }

    public function getViewClass()
    {
        return $this->viewClass;
    }
}
