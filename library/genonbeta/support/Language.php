<?php

namespace genonbeta\support;

use genonbeta\provider\Resource;
use genonbeta\util\Log;

abstract class Language
{
	const TAG = "Language";

	const INFO_AUTHOR = "author";
	const INFO_CHARSET = "charset";
	const INFO_CODENAME = "codename";
	const INFO_LOCATION = "location";
	const INFO_NAME = "language";
	const INFO_TIMEZONE = "timezone";

	private $index = [];
	private $Info = [];
	private $log;

	abstract protected function onLoad();
	abstract protected function onInfo();

	function __construct()
	{
		$this->log = new Log(self::TAG);
		$this->info = $this->onInfo();

		// load files
		$this->onLoad();
	}

	protected function addIndex(array $patch)
	{
		$newArray = array_merge($this->index, $patch);
		$this->index = $newArray;
	}

	public function getInfo()
	{
		return $this->info;
	}

	public function hasString($stringKey)
	{
		return isset($this->index[$stringKey]);
	}

	public function getString($string, array $sprintf = [])
	{
		if($this->hasString($string))
		{
			if(count($sprintf) > 0)
				return call_user_func_array("sprintf", array_merge(array($this->index[$string]), $sprintf));

			return $this->index[$string];
		}

		$this->log->e("{$string} not found");

		return false;
	}

	public function getInterface()
	{
		return $this->langinst;
	}

	function loadFile($fileName)
	{
		if(!is_file($fileName) || !is_readable($fileName))
		{
			$this->log->e("{$fileName} file can't be found or read");
			return false;
		}

		$readIndex = file_get_contents($fileName);
		$jsonIndex = json_decode($readIndex, true);

		if(!$jsonIndex)
			$this->log->e("{$fileName} file cannot be read as a json file");
		else
		{
			$this->addIndex($jsonIndex);
			return true;
		}

		return false;
	}
}
