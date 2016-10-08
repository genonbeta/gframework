<?php

/*
 * SQLite3.php
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

namespace genonbeta\database\model\sqlite3;

use genonbeta\database\DbAdapter;
use genonbeta\database\DbAdapterModel;
use genonbeta\util\StackDataStore;

class SQLite3 extends DbAdapter implements DbAdapterModel
{
	const DB_FILE = "__dbfile__";
	const DB_OPTIONS = "__dboptions__";
	const DB_ENCRYPT_KEY = "__dbencryptkey__";
	const DB_USE_EXTRAS = "__dbuseextras__";

	private $resourceIndex;

	function onStartCommand(StackDataStore $login)
	{
		$fields = $login->getHierarchy();

		if (!$fields[self::DB_USE_EXTRAS])
			$this->resourceIndex = new \SQLite3($fields[self::DB_FILE]);
		else
			$this->resourceIndex = new \SQLite3($fields[self::DB_FILE], $fields[self::DB_OPTIONS], $fields[self::DB_ENCRYPT_KEY]);

		return true;
	}

	function getDbResource()
	{
		return $this->resourceIndex;
	}

	function query()
	{
		$result = $this->callOptimizer(array($this->getDbResource(), "query"), func_get_args());

		if($result instanceof \SQLite3Result) return new SQLite3Result($result);

		return $result;
	}

	function escape()
	{
		$result = $this->callOptimizer(array($this->getDbResource(), "escapeString"), func_get_args());
	}

	function closeConnection()
	{
		$result = $this->callOptimizer(array($this->getDbResource(), "close"), func_get_args());
	}

	function getServerInfo()
	{
		$result = $this->callOptimizer(array($this->getDbResource(), "version"), func_get_args());
	}

	function getDbModelInfo()
	{
		return array("ModelName" => "SQLite3", "ModelVersionCode" => "3");
	}

	function querySingle()
	{
		return $this->callOptimizer(array($this->getDbResource(), "querySingle"), func_get_args());
	}

	function selectDb()
	{
		$result = $this->callOptimizer(array($this->getDbResource(), "open"), func_get_args());
	}

	private function callOptimizer($func, array $index = array())
	{
		if (!method_exists($func[0], $func[1])) return false;

		return call_user_func_array($func, $index);
	}
}
