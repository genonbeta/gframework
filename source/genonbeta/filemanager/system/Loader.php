<?php

namespace genonbeta\filemanager\system;

use Configuration;
use genonbeta\system\System;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\Resource;
use genonbeta\io\RequiredFiles;
use genonbeta\support\Languages;
use genonbeta\util\Log;
use genonbeta\util\NativeUrl;
use genonbeta\database\TmpMemoryHolder;
use genonbeta\view\ViewSkeleton;
use genonbeta\controller\OutputController;
use genonbeta\controller\FlushArgument;
use genonbeta\filemanager\config\MainConfig;

class Loader
{
	const TAG = "Loader";
	
	private $log;
	private $outputController;

	function __construct(array $manifest)
	{
		$this->log = new Log(self::TAG);
		$this->outputController = new OutputController();
		$fwConf = $manifest['configuration'];

		$req = new RequiredFiles(self::TAG);
		$req->request(Configuration::DATA_PATH."/".$fwConf['data_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['languages_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['htmlPatterns_path'], RequiredFiles::TYPE_DIRECTORY);
		$req->request(Configuration::DATA_PATH."/".$fwConf['databases_path'], RequiredFiles::TYPE_DIRECTORY);
		
		ResourceManager::addResource(MainConfig::LANGUAGE_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['languages_path'], "json");
		ResourceManager::addResource(MainConfig::PATTERN_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['htmlPatterns_path'], "html");
		ResourceManager::addResource(MainConfig::DB_INDEX_NAME, Configuration::DATA_PATH."/".$fwConf['databases_path'], "db");

		$patternResource = ResourceManager::getResource(MainConfig::PATTERN_INDEX_NAME);
		
		if(count($manifest['system']['viewSkeleton']) > 0)
		{
			$skeleton = $manifest['system']['viewSkeleton'];
			$path = NativeUrl::pathResolver();
			$pathCount = count($path);
			$leftValues = array();
			$selectedSkeleton = $skeleton[ViewSkeleton::DEFAULT_SKELETON];
			$wayHolder = null;
				
			foreach ($path as $wayNumber => $currentPath)
			{
				($wayNumber === 0) ? $wayHolder = $currentPath : $wayHolder .= "/".$currentPath;
					
				if (isset($skeleton[$wayHolder]))
					if (class_exists($skeleton[$wayHolder]))
					{
						$selectedSkeleton = $skeleton[$wayHolder];
						$leftValues = array_splice($path, $wayNumber);
					}
					
				if($wayNumber > $pathCount)
					break;
			}
					
			if(class_exists($selectedSkeleton))
			{
				$this->log->d("class.{$selectedSkeleton} (ViewSkeleton) loaded for request of manifest file");
				$class = new $selectedSkeleton();

				if(!$class instanceof ViewSkeleton) 
					throw new \InvalidArgumentException(
						"{$defClass} ((Default)ViewSkeleton) not instance of ViewSkeleton"
					);

				$class->onCreate($leftValues);
				$this->outputController->putIndex("systemOutput", $class);
			}
			else
			{
				throw new \InvalidArgumentException(
					"No class was found to load"
				);
			}
		}
		else
		{
			throw new \InvalidArgumentException("No class was defined");
		}
		
		echo $this->outputController->onFlush(FlushArgument::getDefaultArguments());
	}	
}