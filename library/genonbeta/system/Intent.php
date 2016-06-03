<?php

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
	private $staticClass = null;
	
	public function __construct($action = null)
	{
		$this->setAction($action);
		return $this;
	}
	
	public function lockIntentDefault()
	{
		if ($this->staticClass !== null)
			return false;
		
		$this->staticClass = [
			$this->action,
			$this->result,
			$this->extras
		];
		
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
	
	public function removeExtra(string $key)
	{
		if (!$this->hasExtra($key))
			return null;
		
		unset($this->extras[$key]);

		return $this;
	}
	
	public function getExtra($key, bool $default = false)
	{
		if (!$this->hasExtra($key))
			return $default;
			
		return $this->extras[$key];
	}
	
	public function setResult(int $result)
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
	
	public function getAction() : String
	{
		return $this->action;
	}
	
	public function flushOlds()
	{
		if ($this->staticClass === null)
			return null;
		
		$this->action = $this->staticClass[0];
		$this->result = $this->staticClass[1];
		$this->extras = $this->staticClass[2];
		
		return $this;
	}
	
	public static function sendServiceIntent($serviceName, Intent $intent)
	{
		if (!System::serviceExists($serviceName))
			return null;
			
		return System::getService($serviceName)->send($intent);
	}
}
