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
