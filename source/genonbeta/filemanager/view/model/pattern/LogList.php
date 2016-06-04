<?php

namespace genonbeta\filemanager\view\model\pattern;

use genonbeta\view\ViewPattern;
use genonbeta\util\Log;
use genonbeta\lang\StringBuilder;
use genonbeta\provider\AssetResource;
use genonbeta\view\Pattern;
use genonbeta\filemanager\config\MainConfig;

class LogList extends ViewPattern
{
	private $itemList = array("debug", "error", "info");

	function onCreatingPattern()
	{
		return Pattern::getPatternFromResource(MainConfig::PATTERN_INDEX_NAME, "log_list");
	}

	function onControllingItems(array $items)
	{
		$items[Log::TYPE] = $this->itemList[$items[Log::TYPE]];
		$items[Log::TIME] = date("H:i:s", $items[Log::TIME]);
		
		return $items;
	}

	function onNotifingItems()
	{
		return array(Log::PID => "bilinmeyen", Log::MSG => "boş", Log::TIME => time(), Log::TYPE => Log::TYPE_DEBUG);
	}
}
