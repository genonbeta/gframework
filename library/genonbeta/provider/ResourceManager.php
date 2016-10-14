<?php

/*
 * ResourceManager.php
 * 
 * Copyright 2016 Veli TASALI <veli.tasali@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

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
	
	public static function getResource($resourceName, $inResource = true)
	{
		if(!self::resourceExists($resourceName))
			return false;

		if($inResource)
			return new Resource($resourceName);

		return self::$resources[$resourceName];
	}

	private static function getResourceIndex($resourceName)
	{
		$i = self::$resources[$resourceName];
		$type = (!$i['Type']) ? "" : ".".$i['Type'];
		$return = [];

		foreach(glob($i['Directory']."/*". $type) as $resourceResult)
		{
			if(!is_file($resourceResult)) 
				continue;

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
	
	public static function updateResource($resourceName)
	{
		if(!self::resourceExists($resourceName))
			return false;

		self::$resources[$resourceName]['Data'] = self::getResourceIndex($resourceName);
		
		return true;
	}
}
