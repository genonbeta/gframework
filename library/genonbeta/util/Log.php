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

	public function __construct($pid)
	{
		$this->pid = $pid;
	}

	public static function debug($pid, $log)
	{
		self::getLogs()->add(array($pid, $log, time(), self::TYPE_DEBUG));
	}

	public static function error($pid, $log)
	{
		self::getLogs()->add(array($pid, $log, time(), self::TYPE_ERROR));
	}

	public static function info($pid, $log)
	{
		self::getLogs()->add(array($pid, $log, time(), self::TYPE_INFO));
	}

	public function d($log)
	{
		self::getLogs()->add(array($this->pid, $log, time(), self::TYPE_DEBUG));
	}

	public function e($error)
	{
		self::getLogs()->add(array($this->pid, $error, time(), self::TYPE_ERROR));
	}

	public function i($info)
	{
		self::getLogs()->add(array($this->pid, $info, time(), self::TYPE_INFO));
	}

	public static function getLogs()
	{
		if(self::$logs == null)
			self::$logs = new HashMap();

		return self::$logs;
	}
}
