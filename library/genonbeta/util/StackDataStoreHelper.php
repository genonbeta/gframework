<?php

/*
 * StackDataStoreHelper.php
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

use genonbeta\controller\Callback;

abstract class StackDataStoreHelper
{
	const TAG = "StackDataStoreHelper";

	const NON_REQUIRED = false;
	const REQUIRED = true;

	const ERROR_CAUSE_EMPTY = 4453;
	const ERROR_CAUSE_REFUSED = 4597;

	private $stack = [];
	private $log;
	private $lastErrorStack;

	abstract function onCreate();

	function __construct()
	{
		$this->log = new Log(self::TAG);
		$this->onCreate();
	}


	protected function addField($stackName, Callback $callback, $required = false)
	{
		$this->stack[$stackName] = array(
			"callback" => $callback,
			"required" => $required
		);
	}

	public function fieldDefined($fieldName)
	{
		return isset($this->stack[$fieldName]);
	}

	function getCount()
	{
		return count($this->stack);
	}

	public function getFieldNames()
	{
		if(count($this->stack) < 1)
			return $this->stack;

		$names = array();

		foreach($this->stack as $name => $defines)
		{
			// value is its require value
			$names[$name] = $defines['required'];
		}

		return $this->stack;
	}

	public function getCallback($name)
	{
		if(!$this->fieldDefined($name))
			return false;

		return $this->stack[$name]['callback'];
	}

	public function getRequireStat($name)
	{
		if(!$this->fieldDefined($name))
			return false;

		return $this->stack[$name]['required'];
	}

	public function checkAndDone(array $compare)
	{
		if($this->getCount() < 1)
			return true;

		$errorStack = new ErrorStack(self::TAG);

		foreach($this->stack as $name => $defines)
		{
			if(!isset($compare[$name]) && $defines['required'] == self::REQUIRED)
			{
				$this->log->d("As {$name} field is defined as required, it cannot be empty");
				$errorStack->putError($name, self::ERROR_CAUSE_EMPTY);
			}

			$compared = @$compare[$name];

			if($defines['callback']->onCallback($compared) == false)
			{
				$this->log->d("The callback of {$name} field refused its value");
				$errorStack->putError($name, self::ERROR_CAUSE_REFUSED);
			}
		}

		if($errorStack->hasError())
		{
			$this->lastErrorStack = $errorStack;
			return false;
		}

		return true;
	}

	function getLatestErrorStack()
	{
		return $this->lastErrorStack;
	}
}
