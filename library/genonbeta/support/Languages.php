<?php

namespace genonbeta\support;

use genonbeta\provider\Resource;
use genonbeta\util\Log;

class Languages
{
	const TAG = "Languages";
	
	private $fields = array();
	private $resorce;
	private $logs;
	
	function __construct(Resource $resource)
	{
		$this->resorce = $resource;
		$this->logs = new Log(self::TAG);
	}

	function loadFile($fileName)
	{
		$index = $this->resorce->findByName($fileName);

		if($index)
		{
			$readIndex = file_get_contents($index);
			$jsonIndex = json_decode($readIndex, true);

			if(!$jsonIndex)
			{
				$this->logs("{$fileName} throwing an error when decoding its index");
				return false;
			}
			else
				$this->addIndex($jsonIndex);
		}
		else
		{
			$this->logs->e("{$fileName} cannot be find in resources");
			return false;
		}
	}

	private function addIndex(array $patch)
	{
		$newArray = array_merge($this->fields, $patch);
		$this->fields = $newArray;
	}

	public function getString($string, array $sprintf = array())
	{
		if(isset($this->fields[$string])) 
		{
			if(count($sprintf) > 0)
			{
				return call_user_func_array("sprintf", array_merge(array($this->fields[$string]), $sprintf));
			}

			return $this->fields[$string];
		}

		$this->logs->e("{$string} not found");

		return false;
	}
}