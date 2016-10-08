<?php

/*
 * ErrorHandlerHelper.php
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
