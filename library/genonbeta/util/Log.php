<?php

namespace genonbeta\util;

class Log
{

	static private $logs;

	const TYPE_DEBUG = 0;
	const TYPE_ERROR = 1;
	const TYPE_INFO = 2;

	const PID = 0;
	const MSG = 1;
	const TIME = 2;
	const TYPE = 3;

	private $pid;

	public function __construct($pid)
	{
		if($pid == null || !is_string($pid)) return false;
		if(self::$logs == null) self::$logs = new HashMap;

		$this->pid = $pid; 
	}

	public function d($log)
	{
		if(!is_string($log)) return false;

		self::$logs->add(array($this->pid, $log, time(), self::TYPE_DEBUG));

		return true;
	}

	public function e($error)
	{
		if(!is_string($error)) return false;

		self::$logs->add(array($this->pid, $error, time(), self::TYPE_ERROR));

		return true;
	}
	
	public function i($info)
	{
		if(!is_string($info)) return false;

		self::$logs->add(array($this->pid, $info, time(), self::TYPE_INFO));

		return true;
	}


	public static function getLogs()
	{

		if(self::$logs == null) return false;
		if(self::$logs->size() == 0) return false;

		return self::$logs;
	}

}
