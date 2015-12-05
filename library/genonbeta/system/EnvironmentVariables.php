<?php

namespace genonbeta\system;

final class EnvironmentVariables
{
	private static $variables = [];
	
	static function getList()
	{
		return self::$variables;
	}

	static function define(\string $key, $value)
	{
		if(self::isDefined($key))
			return false;
		
		self::$variables[$key] = $value;
		
		return true;
	}
	
	static function isDefined(\string $key)
	{
		if(isset(self::$variables[$key]))
			return true;
		
		return false;
	}
	
	static function get()
	{
		if(!self::isDefined($key))
			return false;
		
		return self::$variables[$key];
	}
}