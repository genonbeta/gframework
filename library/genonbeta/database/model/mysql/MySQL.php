<?php

namespace genonbeta\database\model\mysql;

use genonbeta\database\DbAdapter;
use genonbeta\database\DbAdapterModel;
use genonbeta\util\StackDataStore;

class MySQL extends DbAdapter implements DbAdapterModel
{

	private $resourceIndex;

	const DB_FILE = "__dbfile__";
	const DB_NAME = "__dbname__";
	const DB_SERVER = "__dbserver__";
	const DB_USERNAME = "__dbuser__";
	const DB_PASSWORD = "__dbpass__";

	function onStartCommand(StackDataStore $login)
	{
		$fields = $login->getHierarchy();
		$this->resourceIndex = @mysqli_connect($fields[self::DB_SERVER], $fields[self::DB_USERNAME], $fields[self::DB_PASSWORD]);

		if(!$this->resourceIndex)
            return false;

		return true;
	}

	function getDbResource()
	{
		return $this->resourceIndex;
	}

	function query()
	{
		$result = $this->callOptimizer("mysqli_query", func_get_args());

		if(is_resource($result)) return new MySQLResult($result);

		return $result;
	}

	function escape()
	{
		return $this->callOptimizer("mysqli_real_escape_string", func_get_args());
	}

	function closeConnection()
	{
		return $this->callOptimizer("mysqli_close", func_get_args());
	}

	function getServerInfo()
	{
		return $this->callOptimizer("mysqli_stat", func_get_args());
	}

	function getDbModelInfo()
	{
		return array("ModelName" => "MySQL");
	}

	function selectDb()
	{
		return $this->callOptimizer("mysqli_select_db", func_get_args());
	}

	private function callOptimizer($func, array $index = array())
	{
		if (!function_exists($func)) return false;

		$finalIndex = array_merge($index, array($this->getDbResource()));

		return call_user_func_array($func, $finalIndex);
	}
}
