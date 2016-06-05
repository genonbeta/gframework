<?php

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
