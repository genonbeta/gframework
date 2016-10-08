<?php

/*
 * SQLite3LoginHelper.php
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
