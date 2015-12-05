<?php

namespace genonbeta\controller;

class FlushArgument
{
	const FLUSH_TABS = "__flushTabs__";

	private static $defaultArguments = [
		self::FLUSH_TABS => ""
	];

	public static function getDefaultArguments()
	{
		return self::$defaultArguments;
	} 

	public static function putArguments(array $arguments)
	{
		if(count($arguments) < 0)
			return false;
		
		foreach($arguments as $key => $value)
		{
			if(isset(self::$defaultArguments[$key]))
				continue;
				
			self::$defaultArguments[$key] = $value;
		}
		
		return true;
	}

}
