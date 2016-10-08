<?php

/*
 * TypeHinting.php
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
