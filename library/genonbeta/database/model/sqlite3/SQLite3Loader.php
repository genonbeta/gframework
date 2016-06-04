<?php

namespace genonbeta\database\model\sqlite3;

use genonbeta\util\StackDataStore;
use genonbeta\database\DbAdapter;
use genonbeta\database\DbLoaderInterface;
use genonbeta\util\RefusedIndexException;

class SQLite3Loader implements DbLoaderInterface
{
	private $dbAdapter;
	private $loginInstance;

	function __construct($file, $useExtras = false, $options = null, $encryptkey = null)
	{
		$this->loginInstance = new StackDataStore(new SQLite3LoginHelper);
		
		$this->loginInstance->setField(SQLite3::DB_FILE, $file);
		$this->loginInstance->setField(SQLite3::DB_OPTIONS, $options);
		$this->loginInstance->setField(SQLite3::DB_ENCRYPT_KEY, $encryptkey);
		$this->loginInstance->setField(SQLite3::DB_USE_EXTRAS, $useExtras);
		
		if(!$this->loginInstance->checkAndDone())
		{
			throw new RefusedIndexException("The database connection variables is malformed");
		}

		$this->dbAdapter = new SQLite3($this->loginInstance);
		
		if(!$this->dbAdapter) 
			return false;
		
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
