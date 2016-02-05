<?php

namespace genonbeta\lang;

class Integer
{

	private $int = 0;

	function __construct(int $int)
	{
		$this->int = $int;
	}
	
	function __toString() 
	{
		return $this->int;
	}

	static function parseInt(string $integer = 0) : int
	{
		return new Integer(intval($integer));
	}
}
