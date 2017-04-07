<?php

namespace genonbeta\system\helper;

use Configuration;
use ZipArchive;

use genonbeta\controller\Controller;
use genonbeta\database\Cache;
use genonbeta\io\File;
use genonbeta\service\AutoLoader;
use genonbeta\system\Component;
use genonbeta\system\System;

class LibraryCacheHelper extends Component implements Controller
{
	const TAG = "LibraryCacheHelper";

	private $parIndex = [];
	private $cache;

	protected function onLoad()
	{
		$this->findPars();
		System::getService("AutoLoader")->putAutoLoader($this);
	}

	protected function getClassId()
	{
		return __CLASS__;
	}

	public function onRequest($className)
	{
		$this->tryToInclude($className);
	}

	public function isCachedLibrary($filePath)
	{
		if(!$this->getCache()->isCachedFile($filePath))
			return false;

		return true;
	}

	public function getParLibraryIndex($fileName)
	{
		$parFile = new File($fileName);

		if(!$parFile->isFile())
			return false;

		$Zip = new ZipArchive();

		if(!$Zip->open($fileName))
			return false;

		for ($i=0; $i < $Zip->numFiles; $i++)
		{
			$Name = $Zip->statIndex($i)['name'];

			if(substr($Name, -1) == "/")
				continue;

			$filesArray[$Name] = $i;
		}

		$this->getCache()->makeCacheFromFile($parFile->getPath(), json_encode($filesArray));

		return true;
	}

	function readParCacheFile($parFile)
	{
		if(!$this->getCache()->isCachedFile($parFile))
			return false;

		return json_decode($this->getCache()->readFileCache($parFile), 1);
	}

	function tryToInclude($className)
	{
		if(count($this->parIndex) < 1)
			return false;

		foreach($this->parIndex as $packageName => $attributes)
		{
			if(!isset($attributes['classes'][$className]))
				continue;

			$nameMerged = $attributes['path']."/".$className;

			if(!$this->getCache()->isCachedFile($nameMerged))
			{
				$readPar = new ZipArchive();
				$readPar->open($attributes['path']);
				$this->getCache()->makeCacheFromFile($nameMerged, $readPar->getFromIndex($attributes['classes'][$className]), true);
				$readPar->close();
			}

			include_once($this->getCache()->getFileCachePath($nameMerged));
		}
	}

	private function findPars()
	{
		$findPars = array_merge(glob(Configuration::SOURCE_PATH."/*.par"), glob(Configuration::LIBRARY_PATH."/*.par"));

		if($findPars < 1)
			return false;

		foreach($findPars as $fileName)
		{
			if(!$this->isCachedLibrary($fileName))
				if (!$this->getParLibraryIndex($fileName))
					continue;

			$this->parIndex[basename($fileName)] = [
				"classes" => $this->readParCacheFile($fileName),
				"path" => $fileName
			];
		}
	}

	private function getCache()
	{
		if ($this->cache == null)
			$this->cache = new Cache(self::TAG);

		return $this->cache;
	}
}
