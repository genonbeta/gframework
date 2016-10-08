<?php

/*
 * MySQLLoader.php
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

namespace genonbeta\database\model\mysql;

use genonbeta\util\StackDataStore;
use genonbeta\database\DbAdapter;
use genonbeta\database\DbLoaderInterface;
use genonbeta\util\RefusedIndexException;

class MySQLLoader implements DbLoaderInterface
{
	private $dbAdapter;
	private $loginInstance;

	function __construct($server = false, $user = false, $passwd = false)
	{
		$this->loginInstance = new StackDataStore(new MySQLLoginHelper);

		$this->loginInstance->setField(MySQL::DB_SERVER, $server);
		$this->loginInstance->setField(MySQL::DB_USERNAME, $user);
		$this->loginInstance->setField(MySQL::DB_PASSWORD, $passwd);

		if(!$this->loginInstance->checkAndDone())
		{
			throw new RefusedIndexException("The database connection variables is malformed");
		}

		$this->dbAdapter = new MySQL($this->loginInstance);

		if(!$this->dbAdapter) return false;
		return true;
	}

	function getLoginInstance()
	{
		return $this->loginInstance;
	}

	function getDbInstance()
	{
		return $this->dbAdapter;
 	}

}
