<?php

namespace genonbeta\util;

class ErrorStack
{
	private $tag;
	private $errors = [];

	function __construct(string $tag)
	{
		$this->tag = $tag;
	}

	function getTag() : string
	{
		return $this->tag;
	}

	function putError(string $cause, int $errorCode)
	{
		$this->errors[$cause] = $errorCode;
		return true;		
	}

	function hasError() : bool
	{
		return ($this->getCount() > 0);
	}

	function getCount() : int
	{
		return count($this->errors);
	}

	function getErrors() : array
	{
		return $this->errors;
	}
}
