<?php

namespace genonbeta\system\helper;

use genonbeta\system\System;

class CurrentManifest
{
	public static function getApplicationInfo()
	{
		return System::getLoadedManifest()['application'];
	}

	public static function getConfiguration()
	{
		return System::getLoadedManifest()['configuration'];
	}

	public static function hasComponents()
	{
		return isset(System::getLoadedManifest()['component']);
	}

	public static function getComponents()
	{
		return System::getLoadedManifest()['component'];
	}

	public static function hasServices()
	{
		return isset(System::getLoadedManifest()['service']);
	}

	public static function getServices()
	{
		return System::getLoadedManifest()['service'];
	}

	public static function getViewLoaderClasss()
	{
		return System::getLoadedManifest()['view']['loaderClass'];
	}

	public static function getViewIndex()
	{
		return System::getLoadedManifest()['view']['index'];
	}
}
