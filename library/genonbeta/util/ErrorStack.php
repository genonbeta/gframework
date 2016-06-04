<?php

namespace genonbeta\util;

class ErrorStack
{
	private $tag;
	private $errors = [];

	function __construct($tag)
	{
		$this->tag = $tag;
	}

	function getTag()
	{
		return $this->tag;
	}

	function putError($cause, $errorCode)
	{
		$this->errors[$cause] = $errorCode;
		return true;		
	}

	function hasError()
	{
		return ($this->getCount() > 0);
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
