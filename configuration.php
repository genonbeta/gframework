<?php

/*
 * configuration.php
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

class Configuration
{
	const DATA_PATH = "data"; // data files
	const LIBRARY_PATH = "library"; // system main library
	const SOURCE_PATH = "source"; // system additional library
	const CACHE_PATH = "data/cache"; // system cache
	const RESOURCE_PATH = "resource"; // resource directory
	const FRAMEWORK_JSON = "source/GManifest.json"; // main configuration file will loaded by System
	const GMANIFEST_MAX_SIZE = 10240; // maximum gmanifest file size (defeult = 10kB)
	const CLASS_EXTENSION = "php"; // this will be used to call or identify the classes
	const RESOURCE_PROTOCOL = "res"; // default resource protocol
	const WORKER_URL = "index.php"; // main system file that handles requests

	private static $systemServices = [
		"ErrorHandler" => "\\genonbeta\\service\\ErrorHandler",
		"AutoLoader" => "\\genonbeta\\service\\AutoLoader",
		"Flusher" => "\\genonbeta\service\\Flusher",
		"ClassLoader" => "\\genonbeta\\service\\ClassLoader"
	];

	// Components can be implemented only once
	private static $components = [
		"\\genonbeta\\system\\configuration\\FirstLoadInitializer",
		"\\genonbeta\\system\\helper\\LibraryCacheHelper",
		"\\genonbeta\\provider\\wrapper\\ResourceComponent",
	];

	public static function getServices()
	{
		return self::$systemServices;
	}

	public static function getComponents()
	{
		return self::$components;
	}
}
