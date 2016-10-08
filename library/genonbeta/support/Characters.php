<?php

/*
 * Characters.php
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

/*
This class defines languages letter that not known by php engine how system can use
In example using In spite of  "echo strtoupper(ç)" > ç , "new genonbeta\lang\String(ç)->toUpper()" > "Ç"
*/

namespace genonbeta\support;

use genonbeta\util\Log;

class Characters
{
	const TAG = "Characters";

	const TYPE_BIG = "tbig";
	const TYPE_SMALL = "tsmall";
	const TYPE_CLEARED = "tcleared";
	const TYPE_LANG = "tlanguage";

	private static $data = [];
	private $lang;

	function __construct($lang)
	{
		$this->lang = $lang;
	}

	function addMap($bigSt, $smallSt, $clearedSt)
	{
		$log = new Log(self::TAG);

		$hexBig = bin2hex($bigSt);
		$hexSmall = bin2hex($smallSt);

		if(!isset(self::$data[$hexBig]))
		{
			self::$data[$hexBig] = array(self::TYPE_BIG => $bigSt,
												self::TYPE_SMALL => $smallSt,
												self::TYPE_CLEARED => $clearedSt,
												self::TYPE_LANG => $this->lang);
		}
		else
		{
			$log->e("{$bigSt} already saved");
		}

		if(!isset(self::$data[$hexSmall]))
		{
			self::$data[$hexSmall] = array(self::TYPE_BIG => $bigSt,
												self::TYPE_SMALL => $smallSt,
												self::TYPE_CLEARED => $clearedSt,
												self::TYPE_LANG => $this->lang);
		}
		else
		{
			$log->e("{$smallSt} already saved");
		}
	}

	public static function character($letter)
	{
		$hex = bin2hex($letter);

		if(!isset(self::$data[$hex]))
			return false;

		return self::$data[$hex];
	}

	public static function getAllMap()
	{
		return self::$data;
	}
}
