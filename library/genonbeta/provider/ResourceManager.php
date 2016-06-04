<?php

namespace genonbeta\provider;

abstract class ResourceManager
{
	private static $resources = [];

	static function addResource($resourceName, $dirPath, $type = null)
	{
		if(!is_dir($dirPath) || self::resourceExists($resourceName))
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
		return isset(self::$resources[$resourceName]);
	}

	public static function getResource($resourceName, $inResource = true)
	{
		if(!self::resourceExists($resourceName)) 
			return false;

		if($inResource) 
			return new Resource($resourceName);

		return self::$resources[$resourceName];
	}
}
