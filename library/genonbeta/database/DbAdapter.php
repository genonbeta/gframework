<?php

/*
 * DbAdapter.php
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

namespace genonbeta\database;

use genonbeta\util\StackDataStore;
use genonbeta\util\Log;

abstract class DbAdapter
{
	const TAG = "DbAdapter";

	private $log;
	private $loginInterface;

	abstract function onStartCommand(StackDataStore $login);
	abstract function getDbResource();

	function __construct(StackDataStore $login)
	{
		$this->log = new Log(self::TAG);

		if(!$login->checkAndDone())
		{
			$this->log->e("Server connection arguments not accepted");
			return false;
		}

		if(!$this->onStartCommand($login))
		{
			$this->log->e("Server connection failured");
			return false;
		}

		return true;
	}
}
