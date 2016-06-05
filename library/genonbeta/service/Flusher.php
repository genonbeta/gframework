<?php

namespace genonbeta\service;

use genonbeta\provider\Service;
use genonbeta\controller\Controller;
use genonbeta\controller\ControllerPool;
use genonbeta\controller\FlushArgument;
use genonbeta\system\Intent;

class Flusher extends Service implements Controller
{
	private static $defaultArguments;

	private $controllerPool;

	function __construct()
	{
		$this->controllerPool = new ControllerPool();
		$this->controllerPool->addTodoList($this);
	}

	protected function onReceive(Intent $intent)
	{
		return $this->controllerPool->sendRequest($args);
	}

	public function onRequest($arguments)
	{
		$arguments[FlushArgument::FLUSH_TABS] .= "	";
		return $arguments;
	}

	public function getDefaultIntent() {}
}
