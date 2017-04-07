<?php

namespace genonbeta\content;

use genonbeta\system\System;
use genonbeta\util\FlushArgument;
use genonbeta\util\HashMap;
use genonbeta\util\PrintableUtils;

class OutputWrapper implements PrintableObject
{
	private $output;
	private static $counter = 0;

	public function __construct()
	{
		$this->output = new HashMap();
	}

	public function put($outputTitle, $object)
	{
		if (!PrintableUtils::isPrintable($object))
			return false;

		$this->output->add(array($outputTitle, $object));

		return true;
	}

	public function onFlush(FlushArgument $flushArgument)
	{
		$output = null;

		foreach($this->output->getAll() as $object)
			$output .= PrintableUtils::flush($object[1], $flushArgument);

		return $output;
	}
}
