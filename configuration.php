<?php

/*
*	Editable configuration file
*	'genonbeta' software configuration file
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
