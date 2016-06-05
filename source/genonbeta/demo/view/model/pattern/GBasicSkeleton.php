<?php

namespace genonbeta\demo\view\model\pattern;

use genonbeta\view\ViewPattern;
use genonbeta\util\Log;
use genonbeta\lang\StringBuilder;
use genonbeta\provider\AssetResource;
use genonbeta\view\Pattern;
use genonbeta\demo\config\MainConfig;

class GBasicSkeleton extends ViewPattern
{
	const TITLE = "page_title";
	const BODY = "page_body";

	function onCreatingPattern()
	{
		return Pattern::getPatternFromResource(MainConfig::PATTERN_INDEX_NAME, "default_html");
	}

	function onControllingItems(array $items)
	{
		return $items;
	}

	function onNotifingItems()
	{
		return array(self::TITLE => "GFramework 1.0 - Test", self::BODY => "");
	}
}
