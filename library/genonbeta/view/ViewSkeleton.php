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
use genonbeta\net\UrlResolver;
use genonbeta\provider\Resource;
use genonbeta\provider\ResourceManager;
use genonbeta\support\Language;
use genonbeta\support\LanguageInterface;
use genonbeta\util\Log;
use genonbeta\util\HashMap;
use genonbeta\util\NativeUrl;

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

	abstract function onCreate(array $methodName);
	abstract function onOutputWrapper();

	function __construct()
	{
		$this->owrapper = $this->onOutputWrapper();
		$this->logs = new Log(self::TAG);
	}

	public function drawPattern(ViewPattern $pattern, $name, array $items)
	{
		$this->getOutputWrapper()->put($name, $pattern->draw($items));
	}

	public function drawPatternAsAdapter(ViewPattern $pattern, $name, HashMap $map)
	{
		$this->getOutputWrapper()->put($name, $pattern->drawAsAdapter($map));
	}

	public function drawPrintable(PrintableObject $object, $name)
	{
		$this->getOutputWrapper()->put($name, $object);
	}

	public function drawView(ViewInterface $interface, $name, array $items)
	{
		$this->getOutputWrapper()->put($name, $interface->onCreate($items));
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

	function getString($name, array $sprintf = array())
	{
		if ($this->getLanguage() == null)
			return false;

		return $this->getLanguage()->getString($name, $sprintf);
	}

	public function getUrlResolver()
	{
		return $this->urlResolver;
	}

	function getUri($skeleton, $abstractPath = null)
	{
		if($this->getUrlResolver() == null)
			return false;

		return $this->getUrlResolver()->getUri($skeleton, $abstractPath);
	}

	function loadLanguage(Language $language)
	{
		$this->language = $language;
        return true;
	}

	protected function setUrlResolver(UrlResolver $resolver)
	{
		if($resolver == null)
			return false;

		$this->urlResolver = $resolver;

		return true;
	}

	function onHeaderElements()
	{
	}

	function onFlush(array $args)
	{
		return $this->getOutputWrapper()->onFlush($args);
	}
}
