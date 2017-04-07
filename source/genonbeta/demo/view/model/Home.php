<?php

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
