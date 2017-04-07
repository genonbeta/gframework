<?php

namespace genonbeta\view;

use genonbeta\content\OutputWrapper;
use genonbeta\content\URLAddress;
use genonbeta\content\PrintableObject;
use genonbeta\provider\Resource;
use genonbeta\provider\ResourceManager;
use genonbeta\support\Language;
use genonbeta\system\System;
use genonbeta\util\Log;
use genonbeta\util\FlushArgument;
use genonbeta\util\HashMap;
use genonbeta\util\PrintableUtils;

abstract class ViewSkeleton implements ViewInterface
{
	const TAG = "ViewSkeleton";

	private $owrapper;
	private $language;
	private $logs;
	private $flushArgument = [];

	abstract function onCreate(array $methodName);
	abstract function onOutputWrapper();

	function __construct()
	{
		$this->owrapper = $this->onOutputWrapper();
		$this->logs = new Log(self::TAG);
	}

	function onFlush(FlushArgument $flushArgument)
	{
		foreach($this->flushArgument as $key => $arg)
			$flushArgument->putField($key, $arg);

		return PrintableUtils::flush($this->getOutputWrapper(), $flushArgument);
	}

	function onHandleViewPattern($requestedKey)
	{

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

	public function hasGET($key)
	{
		return isset($_GET[$key]);
	}

	public function hasPOST($key)
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

	function getGET($key)
	{
		return $_GET[$key];
	}

	function getHTTPHeader()
	{
		return System::getHTTPHeader();
	}

	function getLanguage()
	{
		if ($this->language == null)
			return false;

		return $this->language;
	}

	public function getOutputWrapper()
	{
		return $this->owrapper;
	}

	function getPOST($key)
	{
		return $_POST[$key];
	}

	function getString($name, array $sprintf = array())
	{
		if ($this->getLanguage() == null)
			return false;

		return $this->getLanguage()->getString($name, $sprintf);
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

	public function redirect(URLAddress $address)
	{
		$this->getHTTPHeader()->addHeader("Location", $address->generate());
	}
}
