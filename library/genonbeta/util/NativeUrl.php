<?php

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
