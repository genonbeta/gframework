<?php

namespace genonbeta\demo\view\model\pattern;

use genonbeta\view\ViewPattern;
use genonbeta\util\Log;
use genonbeta\provider\AssetResource;
use genonbeta\view\Pattern;
use genonbeta\demo\config\MainConfig;

class GBasicSkeleton extends ViewPattern
{
	const TITLE = "page_title";
	const BODY = "page_body";

	function onCreate()
	{
		return Pattern::getPatternFromResource(MainConfig::PATTERN_INDEX_NAME, "default_html");
	}

	function onCheckingItems(array $items)
	{
		return $items;
	}

	function onNotify()
	{
		return [self::TITLE => "GFramework 1.0 - Test", self::BODY => "Body is not entered"];
	}
}
