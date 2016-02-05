<?php

namespace genonbeta\view;

use genonbeta\io\File;
use genonbeta\provider\AssetResource;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\resource\ResourceVariable;
use genonbeta\controller\RealtimeDataProcess;
use genonbeta\system\EnvironmentVariables;

class Pattern implements RealtimeDataProcess
{
	private $pattern;
	
	function __construct(string $pattern)
	{
		foreach (EnvironmentVariables::getList() as $key => $value)
		{
			$pattern = str_replace("{env." .$key. "}", $value, $pattern);
		}
		
		$this->pattern = $pattern;
	}
	
	public static function getPattenFromFile(File $file)
	{
		if (!$file->isFile() || !$file->isReadable())
			return false;
			
		return new Pattern($file->getIndex());
	}
	
	public static function getPatternFromResource(string $resourceName, string $patternId)
	{
		if(!ResourceManager::resourceExists($resourceName))
			return false;
		
		$resource = ResourceManager::getResource($resourceName);
		
		if(!$resource->doesExist($patternId))
			return false;
		
		$resourcePath = $resource->findByName($patternId);
		$resourceFile = new File($resourcePath);
		
		return new Pattern($resourceFile->getIndex());
	}
	
	public static function getPatternAssetResource(string $resource)
	{
		if (!$file = AssetResource::openResource($resource)->getResourceFile())
			return false;
			
		return self::getPattenFromFile($file);
	}
	
	public function onFlush(array $args)
	{
		return $this->pattern;
	}
	
	public function __toString()
	{
		return $this->pattern;
	}
}
