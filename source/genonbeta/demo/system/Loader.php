<?php

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
		$fwConf = CurrentManifest::access("configuration");

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
