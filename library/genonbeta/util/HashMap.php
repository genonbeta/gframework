<?php

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