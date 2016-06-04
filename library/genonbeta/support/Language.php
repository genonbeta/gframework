<?php

namespace genonbeta\support;

use genonbeta\provider\Resource;
use genonbeta\util\Log;

class Language
{
	const TAG = "Language";

	private $fields = [];
	private $resource;
	private $log;

	function __construct(Resource $resource)
	{
		$this->resource = $resource;
		$this->log = new Log(self::TAG);
	}

	function loadFile($fileName)
	{
		$index = $this->resource->findByName($fileName);

		if($index)
		{
			$readIndex = file_get_contents($index);
			$jsonIndex = json_decode($readIndex, true);

			if(!$jsonIndex)
				$this->log->e("{$fileName} file cannot be read as a json file");
			else
			{
				$this->addIndex($jsonIndex);
				return true;
			}
		}
		else
			$this->log->e("{$fileName} cannot be found in resources");

		return false;
	}

	private function addIndex(array $patch)
	{
		$newArray = array_merge($this->fields, $patch);
		$this->fields = $newArray;
	}

	public function getString($string, array $sprintf = [])
	{
		if(isset($this->fields[$string]))
		{
			if(count($sprintf) > 0)
				return call_user_func_array("sprintf", array_merge(array($this->fields[$string]), $sprintf));

			return $this->fields[$string];
		}

		$this->log->e("{$string} not found");

		return false;
	}
}
