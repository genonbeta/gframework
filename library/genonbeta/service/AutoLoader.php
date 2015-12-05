<?php

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
		return $this->controllerPool->sendRequest($intent);
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