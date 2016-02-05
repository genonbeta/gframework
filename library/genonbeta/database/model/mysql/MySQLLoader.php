<?php

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
