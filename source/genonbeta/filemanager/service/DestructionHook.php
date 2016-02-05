<?php

namespace genonbeta\filemanager\service;

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

	protected function onReceive(Intent $intent) : Intent
	{
		throw new \BadFunctionCallException("This operation not supported yet");
	}

	public function getDefaultIntent() : Intent
	{
		throw new \BadFunctionCallException("This operation not supported yet");
	}
}
