<?php

namespace genonbeta\view;

use genonbeta\controller\OutputController;
use genonbeta\util\Log;
use genonbeta\util\HashMap;
use genonbeta\util\NativeUrl;
use genonbeta\support\LanguageInterface;
use genonbeta\support\Language;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\Resource;
use genonbeta\net\UrlResolver;

abstract class ViewSkeleton implements ViewInterface
{
	const TAG = "ViewSkeleton";

	const DEFAULT_SKELETON = "__default";

	const TYPE_REQUEST = 1;
	const TYPE_POST = 2;
	const TYPE_GET = 3;
	const TYPE_FILE = 4;

	private $opcontroller;
	private $languageInstance;
	private $languageIndex;
	private $logs;
	private $uris;

	abstract function onCreate(array $methodName);
	abstract function onOutputController();

	function __construct()
	{
		$this->opcontroller = $this->onOutputController();
		$this->logs = new Log(self::TAG);
	}

	protected function getMethods()
	{
		return NativeUrl::pathResolver();
	}

	protected function getOpController()
	{
		return $this->opcontroller;
	}

	public function drawView(ViewInterface $interface, $name, array $items)
	{
		$this->getOpController()->put($name, $interface->onCreate($items));
	}

	public function drawPattern(ViewPattern $pattern, $name, array $items)
	{
		$this->getOpController()->put($name, $pattern->draw($items));
	}

	public function drawPatternAsAdapter(ViewPattern $pattern, $name, HashMap $map)
	{
		$this->getOpController()->put($name, $pattern->drawAsAdapter($map));
	}

	function getString($name, array $sprintf = array())
	{
		if(!$this->languageIndex instanceof Language)
		{
			$this->logs->e("The languageIndex not defined yet. You need to load a language file");
			return null;
		}

		return $this->languageIndex->getString($name, $sprintf);
	}

	function getLoadedLangInfo()
	{
		if(!$this->languageInstance instanceof LanguageInterface)
		{
			$this->logs->e("The languageIndex not defined yet. You need to load a language file");
			return array();
		}

		return $this->languageInstance->onInfo();
	}

	function getUri($skeleton, $abstractPath = null)
	{
		if($this->uris == null)
			return false;

		return $this->uris->getUri($skeleton, $abstractPath);
	}

	function loadLanguage(LanguageInterface $interface)
	{
		$this->languageInstance = $interface;
		$this->languageIndex = $this->languageInstance->onLoading();

		if(!$this->languageIndex instanceof Language)
		{
			$this->logs->e("The language cannot be preserved languages instance");
			return false;
		}

        return true;
	}

	protected function setUrlResolver(UrlResolver $res)
	{
		if($res == null)
		{
			$this->logs->e("setUrlResolver: You tried to load NULL index");
			return false;
		}

		$this->uris = $res;

		return true;
	}

	function onHeaderElements()
	{
	}

	function onFlush(array $args)
	{
		return $this->getOpController()->onFlush($args);
	}

	function __toString()
	{
		return $this->getOpController()->printStack();
	}
}
