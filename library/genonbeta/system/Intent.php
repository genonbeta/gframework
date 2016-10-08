<?php

/*
 * Intent.php
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

namespace genonbeta\system;

use genonbeta\system\System;

class Intent
{
	const ACTION_NONE = "action_none";

	const RESULT_OK = 1;
	const RESULT_FALSE = 2;
	const RESULT_NOT_KNOWN = 4;

	private $action = self::ACTION_NONE;
	private $result = self::RESULT_NOT_KNOWN;
	private $extras = [];
	private $staticData = null;

	public function __construct($action = null)
	{
		$this->setAction($action);
		return $this;
	}

	public function lockIntentDefault()
	{
		if ($this->staticData !== null)
			return false;

		$this->staticData = [$this->action, $this->result, $this->extras];

		return $this;
	}

	public function putExtra($key, $value)
	{
		if ($this->hasExtra($key))
			return false;

		$this->extras[$key] = $value;

		return $this;
	}

	public function hasExtra($key)
	{
		return isset($this->extras[$key]);
	}

	public function removeExtra($key)
	{
		if (!$this->hasExtra($key))
			return null;

		unset($this->extras[$key]);

		return $this;
	}

	public function getExtra($key, $default = false)
	{
		if (!$this->hasExtra($key))
			return $default;

		return $this->extras[$key];
	}

	public function setResult($result)
	{
		if (!is_int($result))
			return null;

		$this->result = $result;

		return $this;
	}

	public function getResult()
	{
		return $this->result;
	}

	public function setAction($action)
	{
		if (!is_string($action))
			return null;

		$this->action = $action;

		return $this;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function flushOlds()
	{
		if ($this->staticData === null)
			return null;

		$this->action = $this->staticData[0];
		$this->result = $this->staticData[1];
		$this->extras = $this->staticData[2];

		return $this;
	}

	public static function sendServiceIntent($serviceName, Intent $intent)
	{
		if (!System::serviceExists($serviceName))
			return null;

		return System::getService($serviceName)->send($intent);
	}
}
