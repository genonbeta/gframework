<?php

namespace genonbeta\util;

class Log
{
	private static $logs;

	const TYPE_DEBUG = 0;
	const TYPE_ERROR = 1;
	const TYPE_INFO = 2;

	const PID = 0;
	const MSG = 1;
	const TIME = 2;
	const TYPE = 3;

	private $pid;

	public function __construct(string $pid)
	{
		$this->pid = $pid; 
	}

	public function d(string $log)
	{
		self::getLogging()->add(array($this->pid, $log, time(), self::TYPE_DEBUG));
		return true;
	}

	public function e(string $error)
	{
		self::getLogging()->add(array($this->pid, $error, time(), self::TYPE_ERROR));
		return true;
	}
	
	public function i(string $info)
	{
		self::getLogging()->add(array($this->pid, $info, time(), self::TYPE_INFO));
		return true;
	}

	private static function getLogging() : HashMap
	{
		if(self::$logs == null)
			self::$logs = new HashMap();

		return self::$logs;
	}

	public static function getLogs() : HashMap
	{
		return self::getLogging();
	}
}
