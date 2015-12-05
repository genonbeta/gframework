<?php

namespace genonbeta\util;

class NativeUrl
{
	private $methods;

	function makeMethod($methodName, $method = false)
	{
		if($method == false)
			$method = $methodName;

		if(!is_string($methodName) || !is_string($method))
			return false;
		
		$this->methods[$methodName] = $method;

		return true;
	}

	function read($arguments)
	{
		$varNumb = func_num_args();

		if($varNumb < 1) 
			return false;

		$nurl = $this->getThis();

		if(count($nurl) < 1) 
			return false;

		$arguments = func_get_args();

		foreach($arguments as $id => $arg)
		{
			$method = $this->getMethod($arg) or die("Method not found {$arg}");
			
			if(!preg_match("#{$method}#", $nurl[$id], $result)) 
				return false;

			$return[] = $result[1];
		}

		return $return;
	}

	function getMethod($method)
	{
		return $this->methods[$method];
	}

	function methodExists($methodName)
	{
		if(isset($this->methods[$methodName]) && $this->methods[$methodName] != null) 
			return true;
		
		return false;
	}

	function getThis()
	{
		return self::pathResolver();
	}

	static function pathResolver()
	{
		$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : null;
		$return = array();

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
