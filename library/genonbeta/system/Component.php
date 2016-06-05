<?php

namespace genonbeta\system;

use genonbeta\system\exception\InvalidStatement;

abstract class Component
{
	private static $loadedClasses = [];

	abstract protected function getClassId();
	abstract protected function onLoad();

	final function __construct()
	{
		$this->onLoad();

		if (!is_string($this->getClassId()))
			throw new InvalidStatement("Class ID must be type of a string. ".gettype($this->getClassId())." is given");

		if (isset(self::$loadedClasses[$this->getClassId()]))
			if (self::$loadedClasses[$this->getClassId()] === true)
				throw new InvalidStatement("A Component class can only be loaded once");

		self::$loadedClasses[$this->getClassId()] = true;
	}
}
