<?php

namespace genonbeta\system;

use Configuration;
use genonbeta\provider\Service;
use genonbeta\service\AutoLoader;
use genonbeta\service\ErrorHandler;
use genonbeta\system\Component;
use genonbeta\system\Intent;
use genonbeta\util\HashMap;
use genonbeta\util\Log;

abstract class System
{
	const TAG = "System";

	private static $loadedClasses;
	private static $logs;
	private static $manifestIndex = [];
	private static $services = [];

	public static function setup()
	{
		spl_autoload_register([__CLASS__, "autoLoad"]);
		set_error_handler([__CLASS__, "errorHandler"]);

		if(!is_dir(Configuration::DATA_PATH))
			mkdir(Configuration::DATA_PATH, 0755);

		self::loadServices(Configuration::getServices());
		self::loadComponents(Configuration::getComponents());

		if(is_file(Configuration::FRAMEWORK_JSON))
		{
			if(filesize(Configuration::FRAMEWORK_JSON) < Configuration::GMANIFEST_MAX_SIZE)
			{
				$jsonIndex = file_get_contents(Configuration::FRAMEWORK_JSON);
				$json = json_decode($jsonIndex, true);

				if($json == false)
				{
					self::getLogger()->e("Json file cannot be read");
				}
				else
				{
					self::$manifestIndex = $json;

					if (isset($json['service']))
						self::loadServices($json['service']);
					else
						self::getLogger()->i("No service was requested to load in gmanifest file");

					if (isset($json['component']))
						self::loadComponents($json['component']);
					else
						self::getLogger()->i("No component was requested in gmanifest file");

					if(isset($json['view']['loaderClass']) && class_exists($json['view']['loaderClass']))
					{
						self::getLogger()->d("Loader class found \"".$json['view']['loaderClass']."\"");
						$loader = new $json['view']['loaderClass']();

						if (!$loader instanceof Component)
							throw new \Exception("Loader class must be instance of \\genonbeta\\system\\Component class");
					}
					else
					{
						throw new \Exception("No class loader class found in GManifest file");
					}
				}
			}
			else
			{
				self::getLogger()->e("Json file is too big to open. Its size must be smaller than defined size in config file");
				throw new \Exception("Due to its size, <b>".Configuration::FRAMEWORK_JSON."</b> file cannot be opened.");
			}
		}
		else
		{
			self::getLogger()->d("Json file not found: ".Configuration::FRAMEWORK_JSON);
			throw new \Exception("<b>".Configuration::FRAMEWORK_JSON."</b> file doesn't exist. That's the the deadline");
		}
	}

	public static function getService($serviceName)
	{
		if (!self::serviceExists($serviceName))
		{
			self::getLogger()->e($serviceName." service which was requested is not known by System");

			return null;
		}

		return self::$services[$serviceName];
	}

	protected static function autoLoad($className)
	{
		$classNameOriginal = $className;
		$className = str_replace("\\", "/", $className);
		$className = preg_replace("/[^a-zA-Z0-9\/]/si", "", $className).".".Configuration::CLASS_EXTENSION;

		if (is_file(Configuration::LIBRARY_PATH."/".$className))
			include_once(Configuration::LIBRARY_PATH."/".$className);
		elseif (is_file(Configuration::SOURCE_PATH."/".$className))
			include_once(Configuration::SOURCE_PATH."/".$className);
		else
			Intent::sendServiceIntent("AutoLoader", self::getService("AutoLoader")
									  ->getDefaultIntent()
									  ->putExtra(AutoLoader::CLASS_NAME, $className));

		self::addLoadedClass($classNameOriginal);

		return true;
	}

	private static function addLoadedClass($className)
	{
		// The character "\" represents to jump to main way
		$className = "\\" . $className;

		$stat = class_exists($className, false) || interface_exists($className, false);

		if($stat === false)
			self::getLogger()->e("{$className} class (or interface) was not found");

		self::getLoadedClasses()->add(array($className, $stat));

		return $stat;
	}

	private static function loadServices(array $serviceList)
	{
		if (count($serviceList) < 1)
		{
			self::getLogger()->d("No service was found to load in array variable");
			return false;
		}

		foreach($serviceList as $serviceName => $serviceClass)
		{
			if (class_exists($serviceClass) && is_string($serviceName))
			{
				if(self::serviceExists($serviceName))
				{
					self::getLogger()->e("Tried to load existed service {$serviceName}");
					continue;
				}

				$serviceInstance = new $serviceClass;

				if ($serviceInstance instanceof Service)
				{
					self::getLogger()->i($serviceName.":".$serviceClass." service is loaded");
					self::$services[$serviceName] = $serviceInstance;
				}
				else
					throw new \Exception($serviceClass." class can only be instance of Service");
			}
		}

        return true;
	}

	public static function serviceExists($serviceName)
	{
		return isset(self::$services[$serviceName]);
	}

	private static function loadComponents(array $componentList)
	{
		if (count($componentList) < 1)
		{
			self::getLogger()->d("No component was found to load in array variable");
			return false;
		}

		foreach ($componentList as $component)
			if (class_exists($component))
			{
				$componentInstance = new $component;

				if ($componentInstance instanceof Component)
					self::getLogger()->i($component." component is loaded");
                else
					throw new \Exception($component." class can only be instance of Component");
			}

        return true;
	}

	public static function getClassStorage($className)
    {
		if (!class_exists($className))
		{
			self::getLogger()->e("Requested self space path of not existing {$className} class");
			return null;
		}

		$className = str_replace("\\", "/", $className);
		$className = preg_replace("/[^a-zA-Z0-9\/]/si", "", $className);

		$spaceDir = new \genonbeta\io\File(Configuration::DATA_PATH."/".$className);

		if (!$spaceDir->doesExist())
			$spaceDir->createDirectories();

		return $spaceDir->getPath();
	}

	public static function errorHandler($errLevel = null, $errMessage = null)
	{
		return Intent::sendServiceIntent("ErrorHandler", self::getService("ErrorHandler")
										 ->getDefaultIntent()
										 ->putExtra(ErrorHandler::ERROR_LEVEL, $errLevel)
										 ->putExtra(ErrorHandler::ERROR_MESSAGE, $errMessage));
	}

	public static function getLoadedClasses()
	{
		if (self::$loadedClasses == null)
			self::$loadedClasses = new HashMap();

		return self::$loadedClasses;
	}

	private static function getLogger()
	{
		if (self::$logs == null)
			self::$logs = new Log(self::TAG);

		return self::$logs;
	}

	public static function getLoadedManifest()
	{
		return self::$manifestIndex;
	}
}
