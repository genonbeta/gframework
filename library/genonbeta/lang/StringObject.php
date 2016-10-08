<?php

/*
 * StringObject.php
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

namespace genonbeta\lang;

use genonbeta\support\Characters;

class StringObject
{

	private $string = null;

	function __construct($string)
	{
		$this->string = $string;
	}

	function replace($rplFrom, $rplTo)
	{
		return new StringObject(str_replace($rplFrom, $rplTo, $this->string));
	}

	function toUpper()
	{
		$str = $this->string;

		if(count(Characters::getAllMap()) > 0)
		{
			foreach(Characters::getAllMap() as $hexName => $letter)
			{
				$str = str_replace($letter[Characters::TYPE_SMALL], $letter[Characters::TYPE_BIG], $str);
			}
		}

		return new StringObject(strtoupper($str));
	}

	function toLower()
	{
		$str = $this->string;

		if(count(Characters::getAllMap()) > 0)
		{
			foreach(Characters::getAllMap() as $hexName => $letter)
			{
				$str = str_replace($letter[Characters::TYPE_BIG], $letter[Characters::TYPE_SMALL], $str);
			}
		}

		return new StringObject(strtolower($str));
	}

	function explode($by)
	{
		return explode($by, $this->string);
	}

	function sub($v1, $v2 = null)
	{
		if(is_int($v1) && !is_int($v2))
		{
			$result = substr($this->string, $v1);
		}
		elseif(is_int($v1) && is_int($v2))
		{
			$result = substr($this->string, $v1, $v2);
		}
		else
		{
			$result = $this->text;
		}

		return new StringObject($result);
	}

	function lenght()
	{
		return strlen($this->string);
	}

	function __toString()
	{
		return $this->string;
	}

	# This method creates a new string object and returns it
	public function __invoke($string)
	{
		return new StringObject($string);
	}
}
