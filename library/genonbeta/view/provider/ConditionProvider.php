<?php

namespace genonbeta\view\provider;


// wair for a second
class ConditionProvider implements SourceProviderObject
{
    const PROVIDER_NAME = "condition";
    const TAG = "ConditionProvider";

    public function getProviderName()
    {
        return self::PROVIDER_NAME;
    }

    public function onRequest($request)
    {
        if (($condition = explode("?", $request)) > 0)
        {

        }
    }
}
