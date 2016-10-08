<?php

/*
 * ClassLoader.php
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

namespace genonbeta\service;

use genonbeta\system\Intent;
use genonbeta\provider\Service;
use genonbeta\provider\ResourceManager;
use genonbeta\system\System;
use genonbeta\util\Log;
use genonbeta\util\HashMap;

class ClassLoader extends Service
{
	const TAG = "ClassLoader";
	const RES_NAME = "ClassLoader";
	const RES_FILETYPE = "ld";

	private $loadedClasses = [];

	function __construct()
	{
		$log = new Log(self::TAG);
		$this->loadedClasses = new HashMap();

		ResourceManager::addResource(self::RES_NAME, System::getClassStorage(__CLASS__), self::RES_FILETYPE);
		$res = ResourceManager::getResource(self::RES_NAME, true);

		foreach ($res->getIndex() as $fileName => $fileInfo)
		{
			$className = str_replace(".", "\\", $fileName);

			if (class_exists($className))
			{
				new $className;

				$log->i(self::TAG.".load({$className}) loaded.");
				$this->loadedClasses->add($className);
			}
			else
				$log->e(self::TAG.".load({$className}) process failed");
		}
	}

	function getLoadedClasses()
	{
		return $this->loadedClasses;
	}

	public function onReceive(Intent $intent) {}
	public function getDefaultIntent() {}
}
