<?php

/*
 * FlushArgument.php
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

namespace genonbeta\util;

class FlushArgument
{
	const FLUSH_TABS = "__flushTabs__";

	private static $defaultArguments = [
		self::FLUSH_TABS => ""
	];

	public static function getDefaultArguments()
	{
		return self::$defaultArguments;
	}

	public static function putArguments(array $arguments)
	{
		if(count($arguments) < 0)
			return false;

		foreach($arguments as $key => $value)
		{
			if(isset(self::$defaultArguments[$key]))
				continue;

			self::$defaultArguments[$key] = $value;
		}

		return true;
	}
}
