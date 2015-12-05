<?php

namespace genonbeta\provider;

abstract class ResourceManager
{
	static private $resources = array();	

	static function addResource($resourceName, $dirPath, $type = false)
	{
		if(!is_string($resourceName) || !is_dir($dirPath) || (!is_string($type) && !is_bool($type)) || self::resourceExists($resourceName))
			return false;
		
		self::$resources[$resourceName] = array(
			"Directory" => $dirPath,
			"Type" => $type,
			"ResourceName" => $resourceName
		);

		self::$resources[$resourceName]['Data'] = self::getResourceIndex($resourceName);
	}

	private static function getResourceIndex($resourceName)
	{
		$i = self::$resources[$resourceName];
		$type = (!$i['Type']) ? "" : ".".$i['Type'];
		$return = array();

		foreach(glob($i['Directory']."/*". $type) as $resourceResult)
		{
			if(!is_file($resourceResult)) continue;

			$rInfo = pathinfo($resourceResult);
			unset($rInfo['dirname']);
			$return[$rInfo['filename']] = $rInfo;
		}

		return $return;
	} 
	
	public static function resourceExists($resourceName)
	{
		if(isset(self::$resources[$resourceName])) 
			return true;
		
		return false;
	}

	public static function getResource($resourceName, $inResource = true) 
	{
		if(!self::resourceExists($resourceName)) 
			return false;
		
		if(!is_bool($inResource)) 
			$inResource = true;

		if($inResource) 
			return new Resource($resourceName);

		return self::$resources[$resourceName];
	}
}