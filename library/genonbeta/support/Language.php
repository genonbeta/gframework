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

class Language
{
	const TAG = "Language";

	private $fields = [];
	private $resource;
	private $log;

	function __construct(Resource $resource)
	{
		$this->resource = $resource;
		$this->log = new Log(self::TAG);
	}

	function loadFile($fileName)
	{
		$index = $this->resource->findByName($fileName);

		if($index)
		{
			$readIndex = file_get_contents($index);
			$jsonIndex = json_decode($readIndex, true);

			if(!$jsonIndex)
				$this->log->e("{$fileName} file cannot be read as a json file");
			else
			{
				$this->addIndex($jsonIndex);
				return true;
			}
		}
		else
			$this->log->e("{$fileName} cannot be found in resources");

		return false;
	}

	private function addIndex(array $patch)
	{
		$newArray = array_merge($this->fields, $patch);
		$this->fields = $newArray;
	}

	public function getString($string, array $sprintf = [])
	{
		if(isset($this->fields[$string]))
		{
			if(count($sprintf) > 0)
				return call_user_func_array("sprintf", array_merge(array($this->fields[$string]), $sprintf));

			return $this->fields[$string];
		}

		$this->log->e("{$string} not found");

		return false;
	}
}
