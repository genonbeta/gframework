<?php

namespace genonbeta\security;

use genonbeta\system\Intent;
use genonbeta\controller\Controller;
use genonbeta\provider\Service;
use genonbeta\system\System;
use genonbeta\service\ErrorHandler;

/*
@depracated: This class depracated since PHP 7+ provides its features built-in
*/
class TypeHinting extends Service implements Controller
{
	function __construct()
	{
		if (version_compare(PHP_VERSION, "7.0.0", "<"))
			System::getService("ErrorHandler")->putHandlerController($this);
	}
	
	function onRequest($intent)
	{
		if ($intent->getAction() !== ErrorHandler::ACTION_HANDLE_ERROR)
			return false;
		
		$errLevel = $intent->getExtra(ErrorHandler::ERROR_LEVEL);
		$errMessage = $intent->getExtra(ErrorHandler::ERROR_MESSAGE);
		
		if ($errLevel !== E_RECOVERABLE_ERROR)
			return ErrorHandler::RESULT_NOT_KNOWN;
			
		if (!$explode = explode(' ', $errMessage)) 
			return ErrorHandler::RESULT_NOT_KNOWN;
		
		if (preg_match('#Argument (.*?) passed to (.*?) must be an instance of (.*?)\, (.*?) given\, called in (.*?) on line (.*?) and defined#si', $errMessage, $matches))
		{
			if ($matches[3] === "int")
				$matches[3] = "integer";
			
			if ($matches[3] === $matches[4])
				return ErrorHandler::RESULT_OK;
		}
		
		return ErrorHandler::RESULT_NOT_KNOWN;
	}
	
	function onReceive(Intent $intent) {}
	function getDefaultIntent() {}
}
