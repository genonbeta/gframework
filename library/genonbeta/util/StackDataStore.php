<?php

namespace genonbeta\util;

class StackDataStore
{

	const TAG = "StackSataStore";

	protected $log;
	private $data;
	private $selfData = array();

	function __construct(StackDataStoreHelper $data)
	{
		$this->log = new Log(self::TAG);
		$this->data = $data;
	}

	function setField($field, $value)
	{
		if(!$this->data->fieldDefined($field))
		{
			$this->log->e("{$field} not defined ");
			return false;
		}

		$this->selfData[$field] = $value;

		return false;		
	}

	function checkAndDone()
	{
		if($this->data->checkAndDone($this->selfData)) return true;
		return false;
	}

	function getLatestErrorStack()
	{
		return $this->data->getLatestErrorStack();
	}

	function getHierarchy()
	{
		return $this->selfData;
	}
}
