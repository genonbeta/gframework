<?php

namespace genonbeta\demo\system;

use Configuration;
use genonbeta\controller\FlushArgument;
use genonbeta\provider\ResourceManager;
use genonbeta\io\RequiredFiles;
use genonbeta\system\System;
use genonbeta\view\ViewSkeleton;
use genonbeta\demo\config\MainConfig;

class MainLoader extends \genonbeta\core\system\Loader
{
	const TAG = "Loader";

	protected function onCreate()
	{
		$fwConf = System::getLoadedManifest()['configuration'];

		$req = new RequiredFiles(self::TAG);
		$req->request(Configuration::DATA_PATH."/".$fwConf['data_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['languages_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['htmlPatterns_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['databases_path'], RequiredFiles::TYPE_DIRECTORY);

		ResourceManager::addResource(MainConfig::LANGUAGE_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['languages_path'], "json");
		ResourceManager::addResource(MainConfig::PATTERN_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['htmlPatterns_path'], "html");
		ResourceManager::addResource(MainConfig::DB_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['databases_path'], "db");
	}

	protected function onSkeletonLoaded(ViewSkeleton $skeleton)
	{
		$this->getOutputController()->put("systemOutput", $skeleton);

		// Let's output the data =)
		echo $this->getOutputController()->onFlush(FlushArgument::getDefaultArguments());
	}

	protected function onDestroy()
	{
		System::getService("DestructionHook")->load();
	}
}
