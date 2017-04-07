<?php

namespace genonbeta\system;

use genonbeta\util\FlushArgument;

class UniversalMessageFilter
{
	const FILTER_VOID = 0;
	const FILTER_PLAIN_TEXT = 1;
	const FILTER_CODE = 2;

	private static $list = [];

	public static function applyFilter($message, $type, FlushArgument $flushArgument = null)
	{
		foreach(self::$list as $filter)
		{
			if ($filter->getType() != $type)
				continue;

			if ($flushArgument == null)
				$flushArgument = new FlushArgument();

			$message = $filter->applyFilter($message, $flushArgument);
		}

		return $message;
	}

	public static function isRegistered(UniversalMessageFilterObject $filter)
	{
		return isset(self::$list[get_class($filter)]);
	}

	public static function registerFilter(UniversalMessageFilterObject $filter)
	{
		if (self::isRegistered($filter))
			return false;

		self::$list[get_class($filter)] = $filter;

		return true;
	}

	public static function unregisterFilter(UniversalMessageFilterObject $filter)
	{
		if (!self::isRegistered($filter))
			return false;

		unset(self::$list[get_class($filter)]);

		return true;
	}
}
