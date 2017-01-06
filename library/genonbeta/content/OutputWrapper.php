<?php

/*
 * OutputWrapper.php
 *
 * Copyright 2016 Veli TASALI <veli.tasali@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

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

	public function onFlush(FlushArgument $args)
	{
		$output = null;

		foreach($this->output->getAll() as $object)
			$output .= PrintableUtils::getPrintableObject($object[1], $args);

		return $output;
	}
}
