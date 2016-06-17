<?php

namespace genonbeta\view;

use genonbeta\controller\OutputController;
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

	private $opcontroller;
	private $languageInstance;
	private $languageIndex;
	private $urlResolver;
	private $logs;

	abstract function onCreate(array $methodName);
	abstract function onOutputController();

	function __construct()
	{
		$this->opcontroller = $this->onOutputController();
		$this->logs = new Log(self::TAG);
	}

	public function drawPattern(ViewPattern $pattern, $name, array $items)
	{
		$this->getOutputController()->put($name, $pattern->draw($items));
	}

	public function drawPatternAsAdapter(ViewPattern $pattern, $name, HashMap $map)
	{
		$this->getOutputController()->put($name, $pattern->drawAsAdapter($map));
	}

	public function drawView(ViewInterface $interface, $name, array $items)
	{
		$this->getOutputController()->put($name, $interface->onCreate($items));
	}

	function getLanguageInfo()
	{
		if ($this->getLanguageInstance() == null)
			return false;

		return $this->getLanguageInstance()->onInfo();
	}

	public function getLanguageInstance()
	{
		if($this->languageInstance == null || !$this->languageInstance instanceof LanguageInterface)
		{
			$this->logs->e("The language interfaces not defined yet. You need to load a language file");
			return null;
		}

		return $this->languageInstance;
	}

	public function getLanguageIndex()
	{
		if ($this->getLanguageInstance() == null)
			return null;

		return $this->languageIndex;
	}

	public function getMethods()
	{
		return NativeUrl::pathResolver();
	}

	public function getOutputController()
	{
		return $this->opcontroller;
	}

	function getString($name, array $sprintf = array())
	{
		if ($this->getLanguageIndex() == null)
			return false;

		return $this->getLanguageIndex()->getString($name, $sprintf);
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

	function loadLanguage(LanguageInterface $languageInstance)
	{
		$languageIndex = $languageInstance->onLoading();

		if(!$languageIndex instanceof Language)
		{
			$this->logs->e("While loading language, it returned wrong class or something");
			return false;
		}

		$this->languageInstance = $languageInstance;
		$this->languageIndex = $languageIndex;

        return true;
	}

	function onHeaderElements()
	{
	}

	function onFlush(array $args)
	{
		return $this->getOutputController()->onFlush($args);
	}

	protected function setUrlResolver(UrlResolver $resolver)
	{
		if($resolver == null)
			return false;

		$this->urlResolver = $resolver;

		return true;
	}

	function __toString()
	{
		return $this->getOutputController()->printStack();
	}
}
