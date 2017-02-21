<?php

namespace genonbeta\provider;

use genonbeta\content\StaticView;

class ViewRegistry
{
    private static $staticView = [];

    public static function addToStaticView($id, array $items = [], $items2 = false)
    {
        if(($staticView = self::getStaticView($id)) == false)
            return false;

        if (!$items2)
            $staticView->getItemList()->add($items);
        else
        {
            foreach($items2 as $arrayValue)
            {
                if (!is_array($arrayValue))
                    continue;

                $addedItem = [];

                foreach($items as $key => $value)
                    if ($arrayValue[$key])
                        $addedItem[$value] = $arrayValue[$key];

                $staticView->getItemList()->add($addedItem);
            }
        }

        return true;
    }

    public static function createStaticView($id, $className)
    {
        if(self::hasStaticView($id))
            return false;

        self::$staticView[$id] = new StaticView($className);

        return true;
    }

    public static function hasStaticView($id)
    {
        return isset(self::$staticView[$id]);
    }

    public static function getStaticView($id)
    {
        if(!self::hasStaticView($id))
            return false;

        return self::$staticView[$id];
    }
}
