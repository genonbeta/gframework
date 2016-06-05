<?php

namespace genonbeta\service;

use genonbeta\system\Intent;
use genonbeta\provider\Service;
use genonbeta\provider\ResourceManager;
use genonbeta\system\System;
use genonbeta\util\Log;
use genonbeta\util\HashMap;

class ClassLoader extends Service
{
	const TAG = "ClassLoader";
	const RES_NAME = "ClassLoader";
	const RES_FILETYPE = "ld";

	private $loadedClasses = [];

	function __construct()
	{
		$log = new Log(self::TAG);
		$this->loadedClasses = new HashMap();

		ResourceManager::addResource(self::RES_NAME, System::getClassStorage(__CLASS__), self::RES_FILETYPE);
		$res = ResourceManager::getResource(self::RES_NAME, true);

		foreach ($res->getIndex() as $fileName => $fileInfo)
		{
			$className = str_replace(".", "\\", $fileName);

			if (class_exists($className))
			{
				new $className;

				$log->i(self::TAG.".load({$className}) loaded.");
				$this->loadedClasses->add($className);
			}
			else
				$log->e(self::TAG.".load({$className}) process failed");
		}
	}

	function getLoadedClasses()
	{
		return $this->loadedClasses;
	}

	public function onReceive(Intent $intent) {}
	public function getDefaultIntent() {}
}
