<?php

namespace genonbeta\database\model\mysql;

use genonbeta\database\DbAdapter;
use genonbeta\util\StackDataStoreHelper;
use genonbeta\util\StackDataStore;
use genonbeta\database\model\mysql\login\field\Username;
use genonbeta\database\model\mysql\login\field\Password;
use genonbeta\database\model\mysql\login\field\DbName;
use genonbeta\database\model\mysql\login\field\Server;

final class MySQLLoginHelper extends StackDataStoreHelper
{

	function onCreate()
	{
		$this->addField(MySQL::DB_USERNAME, new Username, StackDataStoreHelper::NON_REQUIRED);
		$this->addField(MySQL::DB_PASSWORD, new Password, StackDataStoreHelper::NON_REQUIRED);
		$this->addField(MySQL::DB_NAME, new DbName, StackDataStoreHelper::NON_REQUIRED);
		$this->addField(MySQL::DB_SERVER, new Server, StackDataStoreHelper::NON_REQUIRED);
	}

}
