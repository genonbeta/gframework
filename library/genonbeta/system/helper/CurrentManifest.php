<?php

namespace genonbeta\system\helper;

use genonbeta\system\System;
use genonbeta\util\Log;

class CurrentManifest
{
	public static function access($pathStyle)
	{
		$path = explode("/", $pathStyle);
		$mergedPath = System::getLoadedManifest();

		foreach($path as $value)
		{
			if (isset($mergedPath[$value]))
				$mergedPath = $mergedPath[$value];
			else
			{
				Log::error("Manifest access error path(".$pathStyle.") cannot be followed till the end. This ".$value." hasn't been defined");
				$mergedPath = false;
				break;
			}
		}

		return $mergedPath;
	}

	public static function doesViewExists($viewName)
	{
		return isset(self::getViewIndex()[$viewName]);
	}

	public static function getApplicationInfo()
	{
		return System::getLoadedManifest()['system']['application'];
	}

	public static function hasComponents()
	{
		return isset(System::getLoadedManifest()['system']['component']);
	}

	public static function getComponents()
	{
		return System::getLoadedManifest()['system']['component'];
	}

	public static function hasServices()
	{
		return isset(System::getLoadedManifest()['system']['service']);
	}

	public static function getServices()
	{
		return System::getLoadedManifest()['system']['service'];
	}

	public static function getViewLoaderClass()
	{
		return System::getLoadedManifest()['system']['view']['loaderClass'];
	}

	public static function getViewIndex()
	{
		return System::getLoadedManifest()['system']['view']['index'];
	}
}
