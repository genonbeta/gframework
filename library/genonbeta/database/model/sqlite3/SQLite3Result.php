<?php

namespace genonbeta\database\model\sqlite3;

use genonbeta\database\DbResultModel;
use genonbeta\database\Cursor;
use genonbeta\util\HashMap;

class SQLite3Result implements DbResultModel
{
	
	private $resultIndex;

	function __construct(\SQLite3Result $result)
	{
		$this->resultIndex = $result;
	}

	function fetchArray()
	{
		return $this->callOptimizer(array($this->resultIndex, "fetchArray"), func_get_args());
	}

	function numRows()
	{
		return $this->callOptimizer(array($this->resultIndex, "fetchArray"), func_get_args());
	}
	
	function numColumns()
	{
		return $this->callOptimizer(array($this->resultIndex, "numColumns"), func_get_args());
	}
	
	function result()
	{
		return $this->callUserFunc("mysql_result", func_get_args());
	}
	
	function columnType()
	{
		return $this->callOptimizer(array($this->resultIndex, "columnType"), func_get_args());
	}
	
	function columnName()
	{
		return $this->callOptimizer(array($this->resultIndex, "columnName"), func_get_args());
	}
	
	function finalize()
	{
		return $this->callOptimizer(array($this->resultIndex, "finalize"), func_get_args());
	}
	
	function reset()
	{
		return $this->callOptimizer(array($this->resultIndex, "reset"), func_get_args());
	}

	function numField()
	{
		return $this->callUserFunc("mysql_num_field", func_get_args());
	}

	function getCursor()
	{
		$indexes = new HashMap();
		
		while($item = $this->fetchArray())
		{
			$indexes->add($item);
		}

		return new Cursor($indexes);
	}

	function getHashMap()
	{
		$indexes = new HashMap();
		
		while($item = $this->fetchArray())
		{
			$indexes->add($item);
		}

		return $indexes;
	}

	
	private function callOptimizer($func, array $index = array())
	{
		if (!method_exists($func[0], $func[1])) return false;
		
		return call_user_func_array($func, $index);
	}
}
