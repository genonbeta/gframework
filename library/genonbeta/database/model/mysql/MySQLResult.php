<?php

/*
 * MySQLResult.php
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

namespace genonbeta\database\model\mysql;

use genonbeta\database\DbResultModel;
use genonbeta\database\Cursor;
use genonbeta\util\HashMap;

class MySQLResult implements DbResultModel
{

	private $resultIndex;

	function __construct(resource $result)
	{
		if(!is_resource($result))
		{
			throw new \InvalidArgumentException("excepted resource of MySQL Query");
		}

		$this->resultIndex = $result;
	}

	function fetchArray()
	{
		return $this->callOptimizer("mysql_fetch_array", func_get_args());
	}

	function numRows()
	{
		return $this->callOptimizer("mysql_num_rows", func_get_args());
	}

	function result()
	{
		return $this->callOptimizer("mysql_result", func_get_args());
	}

	function numField()
	{
		return $this->callOptimizer("mysql_num_field", func_get_args());
	}

	function getCursor()
	{
		$indexes = new HashMap();

		while($item = $this->fetchArray())
		{
			$indexes->add($item);
		}

		return new Cursor($indexes);
	}

	function getHashMap()
	{
		$indexes = new HashMap();

		while($item = $this->fetchArray())
		{
			$indexes->add($item);
		}

		return $indexes;
	}

	private function callOptimizer($func, array $index = array())
	{
		if (!function_exists($func)) return false;

		$finalIndex = array_merge($index, array($this->resultIndex));

		return call_user_func_array($func, $finalIndex);
	}
}
