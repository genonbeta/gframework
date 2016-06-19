<?php

namespace genonbeta\util;

use genonbeta\content\PrintableObject;

class PrintableUtils
{
	public static function isPrintable($object)
	{
		return is_string($object) || is_int($object) || ($object instanceof PrintableObject);
	}

	public static function getPrintableObject($object, array $args = [])
	{
		if (!self::isPrintable($object))
			return false;

		return ($object instanceof PrintableObject) ? $object->onFlush($args) : $object;
	}
}
