<?php

namespace genonbeta\lang;

use genonbeta\support\Characters;

class String
{

	private $string = null;

	function __construct(\string $string)
	{
		$this->string = $string;
	}

	function replace(\string $rplFrom, \string $rplTo) 
	{
		return new String(str_replace($rplFrom, $rplTo, $this->string));
	}

	function toUpper()  
	{
		$str = $this->string;

		if(count(Characters::getAllMap()) > 0)
		{
			foreach(Characters::getAllMap() as $hexName => $letter)
			{
				$str = str_replace($letter[Characters::TYPE_SMALL], $letter[Characters::TYPE_BIG], $str);
			}	
		}
	
		return new String(strtoupper($str));
	}

	function toLower()  
	{
		$str = $this->string;

		if(count(Characters::getAllMap()) > 0)
		{
			foreach(Characters::getAllMap() as $hexName => $letter)
			{ 
				$str = str_replace($letter[Characters::TYPE_BIG], $letter[Characters::TYPE_SMALL], $str);
			}	
		}
	
		return new String(strtolower($str));
	}

	function explode(\string $by)  
	{
		return explode($by, $this->string);
	}

	function sub($v1, $v2 = null) 
	{
		if(is_int($v1) && !is_int($v2)) 
		{
			$result = substr($this->string, $v1);
		}
		elseif(is_int($v1) && is_int($v2))
		{
			$result = substr($this->string, $v1, $v2);
		} 
		else 
		{
			$result = $this->text;
		}

		return new String($result);
	}

	function lenght() 
	{
		return strlen($this->string);
	}

	function __toString() 
	{
		return $this->string;
	}
	
	# This method creates a new String object and returns it
	public function __invoke(\string $string)
	{
		return new String($string);
	}
}
