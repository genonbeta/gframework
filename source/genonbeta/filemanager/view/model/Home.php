<?php

namespace genonbeta\filemanager\view\model;

use Configuration;
use genonbeta\controller\OutputController;
use genonbeta\database\model\mysql\MySQLLoader;
use genonbeta\database\model\sqlite3\SQLite3Loader;
use genonbeta\lang\String;
use genonbeta\lang\StringBuilder;
use genonbeta\net\UrlResolver;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\AssetResource;
use genonbeta\system\System;
use genonbeta\util\ResourceHelper;
use genonbeta\util\Log;
use genonbeta\view\ViewSkeleton;

use genonbeta\filemanager\config\MainConfig;
use genonbeta\filemanager\language\Turkish;
use genonbeta\filemanager\view\model\pattern\GBasicSkeleton;
use genonbeta\filemanager\view\model\pattern\LogList;

class Home extends ViewSkeleton
{
	const TAG = "Home";
	
	public function onCreate(array $methods)
	{
		$log = new Log(self::TAG);
		$res = ResourceManager::getResource(MainConfig::DB_INDEX_NAME);
		
		$listPattern = new LogList($this);
		
		try
		{
			$sdbLoader = new SQLite3Loader($res->findByName("tr_en"));
			$sdb = $sdbLoader->getDbInstance();
			$result = $sdb->query("SELECT * FROM `tr_en`");
			$cursor = $result->getCursor();			
						
			$log->i("Veritabanında ".$cursor->getCount()." adet kelime bulunuyor");
		} 
		catch(\Exception $e)
		{
		}
						
		$this->loadLanguage(new Turkish());
		$this->setUrlResolver(new UrlResolver(Configuration::WORKER_URL, System::getLoadedManifest()['system']['viewSkeleton']));
		
		$dbLoader = new MySQLLoader();
		$db = $dbLoader->getDbInstance();
		
		$log->i("<a href=\"" . $this->getUri("about", "?user=23")."\">Goto about page</a>");		
		
		$sb = new StringBuilder();
		$sb->put($listPattern->drawAsAdapter(Log::getLogs()));
		
		$this->drawPattern(new GBasicSkeleton($this), "system_html", array(GBasicSkeleton::TITLE => "Home", GBasicSkeleton::BODY => $sb));
	}
	
	public function outputController()
	{
		return new OutputController();
	}
}