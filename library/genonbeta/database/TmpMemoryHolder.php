<?php

/*
 * TmpMemoryHolder.php
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

namespace genonbeta\database;

# @depracated
abstract class TmpMemoryHolder
{
	static private $data = [];

	static function put($data, $index)
	{
		if(!self::checkDataOnSave($data, $index))
			return false;

		self::$data[$data] = $index;

		return true;
	}

	static function rewrite($data, $index)
	{
		if(!self::checkDataOnRewrite($data, $index))
			return false;

		self::$data[$data] = $index;

		return true;
	}

	static function get($data)
	{
		if(!self::isSaved($data))
			return false;

		return self::$data[$data];
	}

	static protected function checkDataOnSave($data, $index)
	{
		if(!self::checkData($data, $index))
			return false;

		if(isset(self::$data[$data]))
			return false;

		return true;
	}

	static protected function checkDataOnRewrite($data, $index)
	{
		if(!self::checkData($data, $index))
			return false;

		if(!isset(self::$data[$data]))
			return false;

		return true;
	}

	static function isSaved($data)
	{
		if(!is_string($data) || empty($data))
			return false;

		if(!isset(self::$data[$data]))
			return false;

		return true;
	}

	static protected function checkData($data, $index)
	{
		if(!is_string($data))
			return false;

		if(empty($index) || empty($data))
			return false;

		return true;
	}
}
