<?php

/*
 * Language.php
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

namespace genonbeta\support;

use genonbeta\provider\Resource;
use genonbeta\util\Log;

abstract class Language
{
	const TAG = "Language";

	const INFO_AUTHOR = "author";
	const INFO_CHARSET = "charset";
	const INFO_CODENAME = "codename";
	const INFO_LOCATION = "location";
	const INFO_NAME = "language";
	const INFO_TIMEZONE = "timezone";

	private $index = [];
	private $Info = [];
	private $log;

	abstract protected function onLoad();
	abstract protected function onInfo();

	function __construct()
	{
		$this->log = new Log(self::TAG);
		$this->info = $this->onInfo();
		
		// load files
		$this->onLoad();
	}

	protected function addIndex(array $patch)
	{
		$newArray = array_merge($this->index, $patch);
		$this->index = $newArray;
	}
	
	public function getInfo()
	{
		return $this->info;
	}

	public function getString($string, array $sprintf = [])
	{
		if(isset($this->index[$string]))
		{
			if(count($sprintf) > 0)
				return call_user_func_array("sprintf", array_merge(array($this->index[$string]), $sprintf));

			return $this->index[$string];
		}

		$this->log->e("{$string} not found");

		return false;
	}
	
	public function getInterface()
	{
		return $this->langinst;
	}
	
	function loadFile($fileName)
	{
		if(!is_file($fileName) || !is_readable($fileName))
		{
			$this->log->e("{$fileName} file can't be found or read");
			return false;
		}

		$readIndex = file_get_contents($fileName);
		$jsonIndex = json_decode($readIndex, true);

		if(!$jsonIndex)
			$this->log->e("{$fileName} file cannot be read as a json file");
		else
		{
			$this->addIndex($jsonIndex);
			return true;
		}

		return false;
	}
}
