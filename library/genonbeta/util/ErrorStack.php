<?php

namespace genonbeta\util;

class ErrorStack
{
	
	private $tag;
	private $errors = array();

	function __construct($tag)
	{
		$this->tag = $tag;
	}

	function getTag()
	{
		return $this->tag;
	}

	function putErrorIn($cause, $errorCode)
	{
		if(!is_string($cause) || !is_int($errorCode)) return false;
		if(empty($cause) || empty($errorCode)) return false;

		$this->errors[$cause] = $errorCode;

		return true;		
	}

	function hasError()
	{
		if(count($this->errors) < 1)
		{
			return false;
		}

		return true;
	}

	function getCount()
	{
		return count($this->errors);
	}

	function getErrors()
	{
		return $this->errors;
	}
}
