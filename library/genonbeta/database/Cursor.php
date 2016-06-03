<?php

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

	public function moveToFirst() : bool
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

	public function getIndexById(int $id)
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
