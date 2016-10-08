<?php

/*
 * Log.php
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
		self::getLogging()->add(array($pid, $log, time(), self::TYPE_DEBUG));
	}

	public static function error($pid, $log)
	{
		self::getLogging()->add(array($pid, $log, time(), self::TYPE_ERROR));
	}

	public static function info($pid, $log)
	{
		self::getLogging()->add(array($pid, $log, time(), self::TYPE_INFO));
	}

	public function d($log)
	{
		self::getLogging()->add(array($this->pid, $log, time(), self::TYPE_DEBUG));
	}

	public function e($error)
	{
		self::getLogging()->add(array($this->pid, $error, time(), self::TYPE_ERROR));
	}

	public function i($info)
	{
		self::getLogging()->add(array($this->pid, $info, time(), self::TYPE_INFO));
	}

	private static function getLogging()
	{
		if(self::$logs == null)
			self::$logs = new HashMap();

		return self::$logs;
	}

	public static function getLogs()
	{
		return self::getLogging();
	}
}
