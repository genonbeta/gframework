<?php

namespace genonbeta\database\model\sqlite3;

use genonbeta\database\DbAdapter;
use genonbeta\util\StackDataStoreHelper;
use genonbeta\util\StackDataStore;
use genonbeta\database\model\sqlite3\login\field\FilePath;
use genonbeta\database\model\sqlite3\login\field\Options;
use genonbeta\database\model\sqlite3\login\field\EncryptionKey;
use genonbeta\util\model\data\Boolean;

final class SQLite3LoginHelper extends StackDataStoreHelper
{
	function onCreate()
	{
		$this->addField(SQLite3::DB_FILE, new FilePath, StackDataStoreHelper::REQUIRED);
		$this->addField(SQLite3::DB_OPTIONS, new Options, StackDataStoreHelper::NON_REQUIRED);
		$this->addField(SQLite3::DB_ENCRYPT_KEY, new EncryptionKey, StackDataStoreHelper::NON_REQUIRED);
		$this->addField(SQLite3::DB_USE_EXTRAS, new Boolean, StackDataStoreHelper::NON_REQUIRED);
	}
}
