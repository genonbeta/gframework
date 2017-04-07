<?php

namespace genonbeta\service;

use genonbeta\system\Intent;
use genonbeta\controller\Controller;
use genonbeta\controller\ControllerPool;
use genonbeta\controller\Callback;
use genonbeta\provider\Service;
use genonbeta\util\Log;

class ErrorHandler extends Service
{
	const RESULT_OK = 1;
	const RESULT_FALSE = 2;
	const RESULT_NOT_KNOWN = 4;

	const ACTION_HANDLE_ERROR = "genonbeta.errorhandler.action.HANDLE_ERROR";

	const ERROR_LEVEL = "errorLevel";
	const ERROR_MESSAGE = "errorMessage";
	const ERROR_SCRIPT = "errorScriptFile";
	const ERROR_LINE_NUMBER = "errorLineNumber";

	private $controllerPool;
	private $controllerCallback;
	private $errorHandlerIntent;

	function __construct()
	{
		$this->controllerPool = new ControllerPool();
		$this->controllerCallback = new ErrorHandlerHelper();
		$this->errorHandlerIntent = (new Intent(self::ACTION_HANDLE_ERROR))->lockIntentDefault();
	}
	protected function onReceive(Intent $intent)
	{
		Log::error("ErrorHandler", $intent->getExtra(self::ERROR_SCRIPT).":".$intent->getExtra(self::ERROR_LINE_NUMBER)." ".$intent->getExtra(self::ERROR_MESSAGE));

		if($this->controllerPool->getCount() < 1)
            return $this->getDefaultIntent()->setResult(Intent::RESULT_FALSE);

		return $this->getDefaultIntent()->setResult($this->controllerPool->sendRequest($intent, ControllerPool::MODE_ARGUMENT_CALLBACK, $this->controllerCallback) ? Intent::RESULT_OK : Intent::RESULT_FALSE);
	}

	public function putHandlerController(Controller $handler)
	{
		$this->controllerPool->addTodoList($handler);
	}

	public function getDefaultIntent()
	{
		return $this->errorHandlerIntent->flushOlds();
	}
}
