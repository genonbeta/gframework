<?php

namespace genonbeta\provider;

use genonbeta\controller\Controller;

class Resource extends ResourceManager
{
	private $data = array();
	private $count = 0;

	function __construct($resourceName)
	{
		if(!self::resourceExists($resourceName)) 
			return false;

		$this->data = self::getResource($resourceName, false);
		$this->count = count($this->data['Data']);
		
		return true;
	}

	function getResourceId()
	{
		$data = $this->data;
		unset($data['Data']);

		return $data;
	}

	function getResourceFileType()
	{
		return $this->data['Type'];
	}

	function getResourceDir()
	{
		return $this->data['Directory'];
	}

	function findByName($name, $fullIndex = false)
	{
		if(!$this->doesExist($name)) 
			return false;

		if(!$fullIndex === true)
			return $this->data['Directory']."/".$this->data['Data'][$name]['basename'];
		
		return $this->data['Data'][$name];
	}
		
	function getIndex()
	{
		return $this->data['Data'];
	}
	
	function getCount()
	{
		return $this->count;
	}

	function doesExist($name) 
	{
		if(!isset($this->data['Data'][$name])) 
			return false;
		
		return true;
	}
}