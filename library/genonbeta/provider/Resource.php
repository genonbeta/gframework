<?php

namespace genonbeta\provider;

use genonbeta\controller\Controller;

class Resource extends ResourceManager
{
	private $index = [];
	private $count = 0;

	function __construct($resourceName)
	{
		if(!self::resourceExists($resourceName)) 
			return false;

		$this->index = self::getResource($resourceName, false);
		$this->count = count($this->index['Data']);
		
		return true;
	}

	function getResourceId()
	{
		$data = $this->index;
		unset($data['Data']);

		return $data;
	}

	function getResourceFileType()
	{
		return $this->index['Type'];
	}

	function getResourceDir()
	{
		return $this->index['Directory'];
	}

	function findByName($name, $fullIndex = false)
	{
		if(!$this->doesExist($name)) 
			return false;

		if(!$fullIndex === true)
			return $this->index['Directory']."/".$this->index['Data'][$name]['basename'];
		
		return $this->index['Data'][$name];
	}
		
	function getIndex()
	{
		return $this->index['Data'];
	}
	
	function getCount()
	{
		return $this->count;
	}

	function doesExist($name)
	{
		return isset($this->index['Data'][$name]);
	}
}
