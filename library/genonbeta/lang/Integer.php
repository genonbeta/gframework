<?php

namespace genonbeta\lang;

class Integer
{

	private $int = 0;

	function __construct($int)
	{
		$this->int = $int;
	}

	function __toString()
	{
		return $this->int;
	}

	static function parseInt($integer = 0)
	{
		return new Integer(intval($integer));
	}
}
