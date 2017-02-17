<?php

/*
 * ErrorHandler.php
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
