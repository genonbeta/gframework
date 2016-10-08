<?php

/*
 * NativeUrl.php
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

class NativeUrl
{
	private $methods = [];

	public function makeMethod($methodName, $method = null)
	{
		if($method == null)
			$method = $methodName;

		$this->methods[$methodName] = $method;

		return true;
	}

	public function read()
	{
		if(func_num_args() < 1)
			return false;

		$nUrl = $this->getThis();

		if(count($nUrl) < 1)
			return false;

		$arguments = func_get_args();

		foreach($arguments as $id => $arg)
		{
			if (!$this->methodExists($arg))
				break;

			$method = $this->getMethod($arg);

			if(!preg_match("#{$method}#", $nUrl[$id], $result))
				return false;

			$return[] = $result[1];
		}

		return $return;
	}

	public function getMethod($methodName)
	{
		return $this->methods[$methodName];
	}

	public function methodExists($methodName)
	{
		return isset($this->methods[$methodName]);
	}

	public function getThis()
	{
		return self::pathResolver();
	}

	public static function pathResolver()
	{
		$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : null;
		$return = [];

		if($path == null)
			array();

		foreach(explode("/", $path) as $id => $value)
		{
			if((!is_string($value) && !is_int($value)) || $value == null)
				continue;

			$return[] = $value;
		}

		return $return;
	}
}
