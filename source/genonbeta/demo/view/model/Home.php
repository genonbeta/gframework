<?php

/*
 * Home.php
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

namespace genonbeta\demo\view\model;

use Configuration;
use genonbeta\content\OutputWrapper;
use genonbeta\database\model\sqlite3\SQLite3Loader;
use genonbeta\provider\AssetResource;
use genonbeta\provider\ResourceManager;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\System;
use genonbeta\util\ResourceHelper;
use genonbeta\util\Log;
use genonbeta\view\ViewSkeleton;

use genonbeta\demo\config\MainConfig;
use genonbeta\demo\language\Turkish;
use genonbeta\demo\view\model\pattern\GBasicSkeleton;
use genonbeta\demo\view\model\pattern\LogList;

class Home extends ViewSkeleton
{
	const TAG = "Home";

	public function onCreate(array $methods)
	{
		$log = new Log(self::TAG);
		$res = ResourceManager::getResource(MainConfig::DB_INDEX_NAME);

		$listPattern = new LogList($this);
		$queuedString = new \genonbeta\util\QueuedString();

		$queuedString->put("this");
		$queuedString->put("is");
		$queuedString->put("gframework");
		$queuedString->put(":)");
		$queuedString->useSeperator(" ");

		$log->d($queuedString->getString());

		$this->loadLanguage(new Turkish());

		$log->d($this->getString("test_text", ["gframewok"]));

		$this->drawPattern("system_html", new GBasicSkeleton($this), [GBasicSkeleton::TITLE => "Home", GBasicSkeleton::BODY => $listPattern->drawAsAdapter(Log::getLogs())]);
	}

	public function onOutputWrapper()
	{
		return new OutputWrapper();
	}
}
