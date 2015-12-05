<?php

namespace genonbeta\view;

use genonbeta\util\Log;
use genonbeta\util\HashMap;
use genonbeta\util\NativeUrl;
use genonbeta\support\LanguageInterface;
use genonbeta\support\Languages;
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
	abstract function outputController();

	function __construct()
	{
		$this->opcontroller = $this->outputController();
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
		$this->getOpController()->putIndex($name, $interface->onCreate($items));
	}

	public function drawPattern(ViewPattern $pattern, $name, array $items)
	{
		$this->getOpController()->putIndex($name, $pattern->draw($items));
	}

	public function drawPatternAsAdapter(ViewPattern $pattern, $name, HashMap $map)
	{
		$this->getOpController()->putIndex($name, $pattern->drawAsAdapter($map));
	}

	function getString($name, array $sprintf = array())
	{
		if(!$this->languageIndex instanceof Languages)
		{ 
			$this->logs->e("The languageIndex not defined yet. You need to load a language file");
			return false;
		}

		return $this->languageIndex->getString($name, $sprintf);
	}

	function getLoadedLangInfo()
	{
		if(!$this->languageInstance instanceof LanguageInterface)
		{ 
			$this->logs->e("The languageIndex not defined yet. You need to load a language file");
			return false;
		}
	
		return $this->languageInstance->onInfo();
	}

	function getUri(\string $skeleton, \string $abstractPath = null)
	{	
		if($this->uris == null)
			return false;
		
		return $this->uris->getUri($skeleton, $abstractPath);
	}

	function loadLanguage(LanguageInterface $interface)
	{
		$this->languageInstance = $interface;
		$this->languageIndex = $this->languageInstance->onLoading();
		
		if(!$this->languageIndex instanceof Languages)
		{ 
			$this->logs->e("The language cannot be preserved languages instance");
			return false;
		}
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