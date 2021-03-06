<?php

namespace genonbeta\system;

use Configuration;

use genonbeta\content\HTTPHeader;
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

	private static $httpHeader;
	private static $loadedClasses;
	private static $log;
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

				if(!$json)
				{
					throw new \Exception("Manifest file couldn't be read (must be in JSON format)");
				}
				else
				{
					self::$manifestIndex = $json;

					if (isset($json['system']['service']))
						self::loadServices($json['system']['service']);
					else
						self::getLog()->i("No service was requested to load by manifest file");

					if (isset($json['system']['component']))
						self::loadComponents($json['system']['component']);
					else
						self::getLog()->i("No component was requested by manifest file");

					if(isset($json['system']['view']['loaderClass']) && class_exists($json['system']['view']['loaderClass']))
					{
						self::getLog()->d("Loader class found \"".$json['system']['view']['loaderClass']."\"");
						$loader = new $json['system']['view']['loaderClass']();

						if (!$loader instanceof Component)
							throw new \Exception("Loader class must be instance of \\genonbeta\\system\\Component class");
					}
					else
					{
						throw new \Exception("No class loader class found in manifest file");
					}
				}
			}
			else
			{
				throw new \Exception("Due to its size, manifest file cannot be opened.");
			}
		}
		else
		{
			throw new \Exception("Manifest file doesn't exist. A JSON formatted file must be provided that's specified in Configuration class");
		}
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

	public static function errorHandler($errLevel = null, $errMessage = null, $script = null, $lineNumber = null)
	{
		return Intent::sendServiceIntent("ErrorHandler", self::getService("ErrorHandler")
										 ->getDefaultIntent()
										 ->putExtra(ErrorHandler::ERROR_LEVEL, $errLevel)
										 ->putExtra(ErrorHandler::ERROR_MESSAGE, $errMessage)
										 ->putExtra(ErrorHandler::ERROR_SCRIPT, $script)
										 ->putExtra(ErrorHandler::ERROR_LINE_NUMBER, $lineNumber));
	}

	private static function addLoadedClass($className)
	{
		// The character "\" represents to jump to main way
		$className = "\\" . $className;

		$stat = class_exists($className, false) || interface_exists($className, false);

		if($stat === false)
			self::getLog()->e("{$className} class (or interface) was not found");

		self::getLoadedClasses()->add(array($className, $stat));

		return $stat;
	}

	public static function getClassStorage($className)
    {
		if (!class_exists($className))
		{
			self::getLog()->e("As {$className} class doesn't exists, rejected to generate class storage path");
			return null;
		}

		$className = str_replace("\\", "/", $className);
		$className = preg_replace("/[^a-zA-Z0-9\/]/si", "", $className);

		$spaceDir = new \genonbeta\io\File(Configuration::DATA_PATH."/".$className);

		if (!$spaceDir->doesExist())
			$spaceDir->createDirectories();

		return $spaceDir->getPath();
	}

	public static function getHTTPHeader()
	{
		if (self::$httpHeader == null)
			self::$httpHeader = new HTTPHeader();

		return self::$httpHeader;
	}

	public static function getLoadedClasses()
	{
		if (self::$loadedClasses == null)
			self::$loadedClasses = new HashMap();

		return self::$loadedClasses;
	}

	public static function getLoadedManifest()
	{
		return self::$manifestIndex;
	}

	private static function getLog()
	{
		if (self::$log == null)
			self::$log = new Log(self::TAG);

		return self::$log;
	}

	public static function getService($serviceName)
	{
		if (!self::serviceExists($serviceName))
		{
			self::getLog()->e($serviceName." service which was requested is not known by System");
			return null;
		}

		return self::$services[$serviceName];
	}

	private static function loadComponents(array $componentList)
	{
		if (count($componentList) < 1)
			return false;

		foreach ($componentList as $component)
			if (class_exists($component))
			{
				$componentInstance = new $component;

				if ($componentInstance instanceof Component)
					self::getLog()->i($component." component is loaded");
                else
					throw new \Exception($component." class can only be instance of Component");
			}

        return true;
	}

	private static function loadServices(array $serviceList)
	{
		if (count($serviceList) < 1)
			return false;

		foreach($serviceList as $serviceName => $serviceClass)
		{
			if (class_exists($serviceClass) && is_string($serviceName))
			{
				if(self::serviceExists($serviceName))
				{
					self::getLog()->e("Tried to load existed service {$serviceName}");
					continue;
				}

				$serviceInstance = new $serviceClass;

				if ($serviceInstance instanceof Service)
				{
					self::getLog()->i($serviceName.":".$serviceClass." service is loaded");
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
}
