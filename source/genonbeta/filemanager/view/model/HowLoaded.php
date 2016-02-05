<?php

namespace genonbeta\filemanager\view\model;

use Configuration;
use genonbeta\controller\OutputController;
use genonbeta\database\model\mysql\MySQLLoader;
use genonbeta\database\model\sqlite3\SQLite3Loader;
use genonbeta\lang\StringBuilder;
use genonbeta\net\UrlResolver;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\AssetResource;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\System;
use genonbeta\util\ResourceHelper;
use genonbeta\util\Log;
use genonbeta\view\ViewSkeleton;

use genonbeta\filemanager\config\MainConfig;
use genonbeta\filemanager\language\Turkish;
use genonbeta\filemanager\view\model\pattern\GBasicSkeleton;
use genonbeta\filemanager\view\model\pattern\LogList;

class HowLoaded extends ViewSkeleton
{
	const TAG = "HowLoaded";

	public function onCreate(array $methods)
	{
		$log = new Log(self::TAG);
		$res = ResourceManager::getResource(MainConfig::DB_INDEX_NAME);

		$listPattern = new LogList($this);

		$this->loadLanguage(new Turkish());
		$this->setUrlResolver(new UrlResolver(EnvironmentVariables::get("workerAddress"), System::getLoadedManifest()['system']['viewSkeleton']));

		$log->i("<a href=\"" . $this->getUri("home", "")."\">Goto home page</a>");


		$cursor = new \genonbeta\database\Cursor(System::getLoadedClasses());

		if ($cursor->moveToFirst())
		{
			do
			{
				$log->i(($cursor->getIndex()[1] ? "loaded" : "error"). " : " . $cursor->getIndex()[0]);
			}
			while ($cursor->moveToNext());
		}

		$sb = new StringBuilder();
		$sb->put($listPattern->drawAsAdapter(Log::getLogs()));

		$this->drawPattern(new GBasicSkeleton($this), "system_html", array(GBasicSkeleton::TITLE => "How Loaded", GBasicSkeleton::BODY => $sb));
	}

	public function outputController() : OutputController
	{
		return new OutputController();
	}
}
