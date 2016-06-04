<?php

namespace genonbeta\system;

final class EnvironmentVariables
{
	private static $variables = [];
	
	public static function getList()
	{
		return self::$variables;
	}

	public static function define($key, $value)
	{
		if(self::isDefined($key))
			return false;
		
		self::$variables[$key] = $value;
		
		return true;
	}
	
	public static function isDefined($key)
	{
		return isset(self::$variables[$key]);
	}
	
	public static function get($key)
	{
		if(!self::isDefined($key))
			return false;
		
		return self::$variables[$key];
	}
}
