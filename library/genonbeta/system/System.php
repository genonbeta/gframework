<?php

/*
 * System.php
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
						self::getLogger()->i("No service was requested to load by manifest file");

					if (isset($json['system']['component']))
						self::loadComponents($json['system']['component']);
					else
						self::getLogger()->i("No component was requested by manifest file");

					if(isset($json['system']['view']['loaderClass']) && class_exists($json['system']['view']['loaderClass']))
					{
						self::getLogger()->d("Loader class found \"".$json['system']['view']['loaderClass']."\"");
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
			return false;

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
			return false;

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
			self::getLogger()->e("As {$className} class doesn't exists, rejected to generate class storage path");
			return null;
		}

		$className = str_replace("\\", "/", $className);
		$className = preg_replace("/[^a-zA-Z0-9\/]/si", "", $className);

		$spaceDir = new \genonbeta\io\File(Configuration::DATA_PATH."/".$className);

		if (!$spaceDir->doesExist())
			$spaceDir->createDirectories();

		return $spaceDir->getPath();
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
