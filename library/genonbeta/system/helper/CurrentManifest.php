<?php

/*
 * CurrentManifest.php
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

	public static function getViewURL($viewName, array $get = [], $extra = "")
	{
		$endPath = null;

		if (count($get) > 0)
		{
			foreach ($get as $key => $value)
			{
				$endPath .= ($endPath == null) ? "?" : "&";
				$endPath .= $key . "=" . $value;
			}
		}

		if (self::doesViewExists($viewName))
			return G_WORKER_URL. "/" .$viewName.$endPath.$extra;

		return G_WORKER_URL. "#pathNotFound";
	}
}
