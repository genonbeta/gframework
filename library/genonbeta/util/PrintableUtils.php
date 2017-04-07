<?php

namespace genonbeta\util;

use genonbeta\content\PrintableObject;
use genonbeta\util\FlushArgument;

class PrintableUtils
{
	public static function isPrintable($object)
	{
		return is_string($object) || is_int($object) || ($object instanceof PrintableObject);
	}

	public static function flush($object, FlushArgument $flushArgument)
	{
		if (!self::isPrintable($object))
			return false;

		return ($object instanceof PrintableObject) ? FlushArgument::flush($object, $flushArgument) : $object;
	}
}
