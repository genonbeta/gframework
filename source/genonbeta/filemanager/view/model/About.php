<?php

namespace genonbeta\filemanager\view\model;

use genonbeta\view\ViewSkeleton;
use genonbeta\controller\OutputController;
use genonbeta\util\Log;
use genonbeta\lang\String;
use genonbeta\lang\StringBuilder;
use genonbeta\database\model\mysql\MySQLLoader;
use genonbeta\database\model\sqlite3\SQLite3Loader;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\AssetResource;

use genonbeta\filemanager\config\DbList;
use genonbeta\filemanager\view\model\pattern\GBasicSkeleton;
use genonbeta\filemanager\view\model\pattern\LogList;
use genonbeta\filemanager\language\Turkish;

class About extends ViewSkeleton
{
	const TAG = "About";
	
	public function onCreate(array $methods)
	{
		$log = new Log(self::TAG);
		$listPattern = new LogList($this);

		$this->loadLanguage(new Turkish());
		
		$sb = new StringBuilder();
		$sb->put($listPattern->drawAsAdapter(Log::getLogs()));
		
		$this->drawPattern(new GBasicSkeleton($this), "system_html", array(GBasicSkeleton::TITLE => "About", GBasicSkeleton::BODY => $sb));
	}

	public function outputController()
	{
		return new OutputController();
	}
}