<?php

/*
 * Loader.php
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

namespace genonbeta\demo\system;

use Configuration;
use genonbeta\util\FlushArgument;
use genonbeta\io\RequiredFiles;
use genonbeta\provider\ResourceManager;
use genonbeta\system\System;
use genonbeta\system\helper\CurrentManifest;
use genonbeta\util\PrintableUtils;
use genonbeta\view\ViewSkeleton;

use genonbeta\demo\config\MainConfig;

class Loader extends \genonbeta\core\system\Loader
{
	const TAG = "Loader";

	protected function onCreate()
	{
		$fwConf = CurrentManifest::getConfiguration();

		$req = new RequiredFiles(self::TAG);
		$req->request(Configuration::DATA_PATH."/".$fwConf['data_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['language_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['html_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['database_path'], RequiredFiles::TYPE_DIRECTORY);

		ResourceManager::addResource(MainConfig::LANGUAGE_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['language_path'], "json");
		ResourceManager::addResource(MainConfig::PATTERN_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['html_path'], "html");
		ResourceManager::addResource(MainConfig::DB_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['database_path'], "db");
	}

	protected function onSkeletonLoaded(ViewSkeleton $skeleton)
	{
		$this->getOutputWrapper()->put("systemOutput", $skeleton);
		
		// Let's output the data =)
		echo PrintableUtils::flush($this->getOutputWrapper(), new FlushArgument());
	}

	protected function onDestroy()
	{
		System::getService("DestructionHook")->load();
	}
}
