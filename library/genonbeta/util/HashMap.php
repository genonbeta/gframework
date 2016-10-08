<?php

/*
 * HashMap.php
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

class HashMap
{
	private $index = [];
	private $limit = -1;

	public function add($data)
	{
		if(count($this->index) >= $this->limit && $this->limit !== -1)
			return false;

		$this->index[] = $data;
		return true;
	}

	public function addKey($key, $data)
	{
		if(count($this->index) >= $this->limit && $this->limit !== -1)
			return false;

		if ($this->containsKey($key))
			return false;

		$this->index[$key] = $data;
		return true;
	}

	public function get($item)
	{
		return $this->index[$item];
	}

	public function containsKey($key)
	{
		return isset($this->index[$key]);
	}

	public function size()
	{
		return count($this->index);
	}

	public function addAll(HashMap $items)
	{
		if($items->size() < 1) return false;

		foreach($items->getAll() as $itemKey => $itemValue)
		{
			$this->add($itemKey, $itemValue);
		}

		return true;
	}

	public function getAll()
	{
		return $this->index;
	}

	public function clear()
	{
		$this->index = [];
	}

	public function setMaxItemLimit($limit)
	{
		$this->limit = $limit;
	}
}
