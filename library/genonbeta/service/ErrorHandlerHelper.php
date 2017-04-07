<?php

namespace genonbeta\service;

use genonbeta\controller\ControllerCallback;

class ErrorHandlerHelper implements ControllerCallback
{
	private $finalResult = false;

	public function onCallback($args)
	{
		if ($args === ErrorHandler::RESULT_OK)
			$this->finalResult = true;
		elseif ($args === ErrorHandler::RESULT_FALSE)
			$this->finalResult = false;
	}

	public function onResult()
	{
		$result = $this->finalResult;
		$this->finalResult = false;

		return $result;
	}
}
