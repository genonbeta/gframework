<?php

/*
 * MySQLLoginHelper.php
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
