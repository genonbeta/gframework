<?php

/*
 * ControllerPool.php
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

namespace genonbeta\controller;

use genonbeta\util\HashMap;
use genonbeta\util\Log;

class ControllerPool
{
	const MODE_ARGUMENT_LOOP = 1;
	const MODE_ARGUMENT_JUST_SEND = 2;
	const MODE_ARGUMENT_CALLBACK = 4;

	const TAG = "ControllerPool";

	private $controllerInterfaces;
	private $log;

	function __construct()
	{
		$this->controllerInterfaces = new HashMap;
		$this->log = new Log(self::TAG);
	}

	public function addTodoList(Controller $cont)
	{
		$this->controllerInterfaces->add($cont);
	}

	public function clearTodoList()
	{
		$this->controllerInterfaces->clear();
	}

	public function sendRequest($items, $mode = self::MODE_ARGUMENT_LOOP, ControllerCallback $resultCallback = null)
	{
		if ($this->controllerInterfaces->size() < 1)
		{
			$this->log->d("no controller defined to do work");

			if ($mode === self::MODE_ARGUMENT_JUST_SEND)
				return false;
			elseif ($mode === self::MODE_ARGUMENT_LOOP)
				return $items;
		}

		for($i = 0; $i < $this->controllerInterfaces->size(); $i++)
		{
			if ($mode === self::MODE_ARGUMENT_CALLBACK)
				$resultCallback->onCallback($this->controllerInterfaces->get($i)->onRequest($items));
			elseif ($mode === self::MODE_ARGUMENT_JUST_SEND)
				$this->controllerInterfaces->get($i)->onRequest($items);
			elseif ($mode === self::MODE_ARGUMENT_LOOP)
				$items = $this->controllerInterfaces->get($i)->onRequest($items);
		}

		if ($mode === self::MODE_ARGUMENT_JUST_SEND)
			return true;
		elseif ($mode === self::MODE_ARGUMENT_LOOP)
			return $items;
		elseif ($mode === self::MODE_ARGUMENT_CALLBACK)
			return $resultCallback->onResult();
	}

	public function getCount()
	{
		return $this->controllerInterfaces->size();
	}
}
