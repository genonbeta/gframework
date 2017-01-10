<?php

/*
 * ViewSkeleton.php
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

namespace genonbeta\view;

use genonbeta\content\OutputWrapper;
use genonbeta\content\PrintableObject;
use genonbeta\net\URLResolver;
use genonbeta\provider\Resource;
use genonbeta\provider\ResourceManager;
use genonbeta\support\Language;
use genonbeta\support\LanguageInterface;
use genonbeta\util\Log;
use genonbeta\util\FlushArgument;
use genonbeta\util\HashMap;
use genonbeta\util\NativeUrl;
use genonbeta\util\PrintableUtils;

abstract class ViewSkeleton implements ViewInterface
{
	const TAG = "ViewSkeleton";

	const TYPE_REQUEST = 1;
	const TYPE_POST = 2;
	const TYPE_GET = 3;
	const TYPE_FILE = 4;

	private $owrapper;
	private $language;
	private $urlResolver;
	private $logs;
	private $flushArgument = [];

	abstract function onCreate(array $methodName);
	abstract function onOutputWrapper();

	function __construct()
	{
		$this->owrapper = $this->onOutputWrapper();
		$this->logs = new Log(self::TAG);
	}

	public function drawPattern($name, ViewPattern $pattern, array $items)
	{
		$this->getOutputWrapper()->put($name, $pattern->draw($items));
	}

	public function drawPatternAsAdapter($name, ViewPattern $pattern, HashMap $map)
	{
		$this->getOutputWrapper()->put($name, $pattern->drawAsAdapter($map));
	}

	public function drawPrintable($name, PrintableObject $object)
	{
		$this->getOutputWrapper()->put($name, $object);
	}

	public function drawView($name, ViewInterface $interface, array $items)
	{
		$this->getOutputWrapper()->put($name, $interface->onCreate($items));
	}

	public function hasArgument($field)
	{
		return isset($this->flushArgument[$field]);
	}

	public function hasGetValue($key)
	{
		return isset($_GET[$key]);
	}

	public function hasPostValue($key)
	{
		return isset($_POST[$key]);
	}

	public function getArgument($field)
	{
		return $this->hasArgument($field) ? $this->flushArgument[$field] : null;
	}

	public function getArgumentList()
	{
		return $this->flushArgument;
	}

	function getLanguage()
	{
		if ($this->language == null)
			return false;

		return $this->language;
	}

	public function getMethods()
	{
		return NativeUrl::pathResolver();
	}

	public function getOutputWrapper()
	{
		return $this->owrapper;
	}

	function getGetValue($key)
	{
		return $_GET[$key];
	}

	function getPostValue($key)
	{
		return $_POST[$key];
	}

	function getString($name, array $sprintf = array())
	{
		if ($this->getLanguage() == null)
			return false;

		return $this->getLanguage()->getString($name, $sprintf);
	}

	public function getURLResolver()
	{
		return $this->urlResolver;
	}

	function getUri($skeleton, $abstractPath = null)
	{
		if($this->getURLResolver() == null)
			return false;

		return $this->getURLResolver()->getUri($skeleton, $abstractPath);
	}

	protected function loadLanguage(Language $language)
	{
		$this->language = $language;
        return true;
	}

	public function putArgument($key, $value)
	{
		$this->flushArgument[$key] = $value;
	}

	protected function setURLResolver(URLResolver $resolver)
	{
		if($resolver == null)
			return false;

		$this->urlResolver = $resolver;

		return true;
	}

	function onFlush(FlushArgument $flushArgument)
	{
		foreach($this->flushArgument as $key => $arg)
			$flushArgument->putField($key, $arg);

		return PrintableUtils::flush($this->getOutputWrapper(), $flushArgument);
	}
}
