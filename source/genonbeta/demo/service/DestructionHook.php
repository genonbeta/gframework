<?php

/*
 * DestructionHook.php
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

namespace genonbeta\demo\service;

use genonbeta\controller\Controller;
use genonbeta\controller\ControllerPool;
use genonbeta\provider\Service;
use genonbeta\system\Intent;

class DestructionHook extends Service
{
	private $controllerPool;

	public function __construct()
	{
		$this->controllerPool = new ControllerPool();
	}

	function put(Controller $loader)
	{
		$this->controllerPool->addTodoList($loader);
	}

	function load()
	{
		$this->controllerPool->sendRequest(null, ControllerPool::MODE_ARGUMENT_JUST_SEND);
	}

	protected function onReceive(Intent $intent)
	{
		throw new \BadFunctionCallException("This operation not supported yet");
	}

	public function getDefaultIntent()
	{
		throw new \BadFunctionCallException("This operation not supported yet");
	}
}
