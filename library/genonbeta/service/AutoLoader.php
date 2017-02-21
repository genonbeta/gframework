<?php

/*
 * AutoLoader.php
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
use genonbeta\controller\Controller;
use genonbeta\controller\ControllerPool;

class AutoLoader extends Service
{
	const ACTION_LOAD_CLASS = "genonbeta.autoloader.action.LOAD_CLASS";
	const CLASS_NAME = "className";

	private $controllerPool;
	private $autoLoaderIntent;

	function __construct()
	{
		$this->controllerPool = new ControllerPool();
		$this->autoLoaderIntent = (new Intent(self::ACTION_LOAD_CLASS))->lockIntentDefault();
	}

	protected function onReceive(Intent $intent)
	{
		$className = $intent->getExtra(self::CLASS_NAME);
		return $this->getDefaultIntent()->setResult($this->controllerPool->sendRequest($className, ControllerPool::MODE_ARGUMENT_JUST_SEND) ? Intent::RESULT_OK : Intent::RESULT_FALSE);
	}

	function putAutoLoader(Controller $loader)
	{
		$this->controllerPool->addTodoList($loader);
	}

	public function getDefaultIntent()
	{
		return $this->autoLoaderIntent->flushOlds();
	}
}
