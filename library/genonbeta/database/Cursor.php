<?php

/*
 * Cursor.php
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

use genonbeta\util\HashMap;

class Cursor
{
	private $items;
	private $currentPosition = -1;

	public function __construct(HashMap $map)
	{
		$this->items = $map->getAll();
	}

	public function moveToFirst()
	{
		if(count($this->items) == 0)
			return false;

		$this->currentPosition = 0;

		return true;
	}

	public function getIndex()
	{
		return $this->items[$this->currentPosition];
	}

	public function getIndexById($id)
	{
		return $this->items[$this->currentPosition][$id];
	}

	public function getPosition()
	{
		return $this->currentPosition;
	}

	public function getCount()
	{
		return count($this->items);
	}

	public function moveToNext()
	{
		if(($this->currentPosition + 1) == count($this->items))
			return false;

		$this->currentPosition ++;

		return true;
	}

	public function moveToPrevious()
	{
		if(($this->currentPosition - 1) < 0)
			return false;

		$this->currentPosition --;

		return true;
	}

	public function moveToLast()
	{
		$this->currentPosition = (count($this->items) - 1);
	}
}
