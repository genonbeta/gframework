<?php

namespace genonbeta\database;

use Configuration;
use genonbeta\io\File;
use genonbeta\io\RequiredFiles;

class Cache
{
	const TAG = "Cache";

	private $cacheDirectory = null;

	function __construct($dataDirectory)
	{
		$required = new RequiredFiles(self::TAG);
		$required->request(Configuration::CACHE_PATH, RequiredFiles::TYPE_DIRECTORY, 0777);
		$required->request(Configuration::CACHE_PATH."/".$dataDirectory, RequiredFiles::TYPE_DIRECTORY, 0777);

		$this->cacheDirectory = Configuration::CACHE_PATH."/".$dataDirectory;
	}

	protected function convertFileName($fileName)
	{
		return $this->cacheDirectory."/".str_replace("/", "@", $fileName);
	}

	function makeCacheFromFile($filePath, $externalContent = null, $fileNotExists)
	{
		$file = new File($filePath);

		if ((!$file->isFile() || !$file->isReadable()) && $fileNotExists == false)
			return false;

		$cacheFile = new File($this->convertFileName($file->getPath()));

		if ($cacheFile->doesExist())
			return false;

		$cacheFile->createNewFile();

		if(!$cacheFile->isWritable())
			return false;

		if ($externalContent !== null)
			return $cacheFile->putIndex($externalContent);
		else
			return $cacheFile->putIndex($file->getIndex());
	}

	function makeCache($cacheFilename, $content)
	{
		$cacheFile = new File($this->cacheDirectory."/".$cacheFilename);

		if ($cacheFile->doesExist())
			return false;

		$cacheFile->createNewFile();

		if(!$cacheFile->isWritable())
			return false;

		return $cacheFile->putIndex($content);
	}

	function readFileCache($cacheName)
	{
		if(!$this->isCachedFile($cacheName))
			return false;

		$file = new File($this->convertFileName($cacheName));

		if(!$file->isFile() || !$file->isReadable())
			return false;

		return $file->getIndex();
	}

	function readCache($cacheName)
	{
		if(!$this->isCached($cacheName))
			return false;

		$file = new File($this->cacheDirectory."/".$cacheName);

		if(!$file->isFile() || !$file->isReadable())
			return false;

		return $file->getIndex();
	}

	function getFileCachePath($cacheName)
	{
		return $this->convertFileName($cacheName);
	}

	function isCachedFile($cacheName)
	{
		if(is_file($this->convertFileName($cacheName)))
			return true;

		return false;
	}

	function isCached($cacheName)
	{
		if(is_file($this->cacheDirectory."/".$cacheName))
			return true;

		return false;
	}

	function deleteFileCache($cacheName)
	{
		$file = new File($this->convertFileName($cacheName));

		if(!$file->isWritable() || !$file->isFile())
			return false;

		return $file->deleteFile();
	}

	function deleteCache($cacheName)
	{
		$file = new File($this->cacheDirectory."/".$cacheName);

		if(!$file->isWritable() || !$file->isFile())
			return false;

		return $file->deleteFile();
	}
}
