<?php

/*
 * ErrorStack.php
 * 
 * Copyright 2016 Veli TASALI <veli.tasali@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

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
