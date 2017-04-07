<?php

namespace genonbeta\util;

use genonbeta\content\PrintableObject;

class FlushArgument
{
	private $tempFields = [];
	private $loopingTime = 0;
	private $lockData = false;

	public static function flush(PrintableObject $printable, FlushArgument $flushArgument)
	{
		$tempFields = $flushArgument->getFieldList();

		$flushArgument->prepare();
		$output = $printable->onFlush($flushArgument);
		$flushArgument->loop($tempFields);

		return $output;
	}

	public function getField($field)
	{
		return $this->hasField($field) ? $this->tempFields[$field] : null;
	}

	public function getFieldList()
	{
		return $this->tempFields;
	}

	public function hasField($field)
	{
		return isset($this->tempFields[$field]);
	}

	public function getLoopingTime()
	{
		return $this->loopingTime;
	}

	public function preventItemRemoving($isLocked)
	{
		$this->lockData = $isLocked ? true : false;
	}

	protected function loop(array $tempFields = [])
	{
		$this->loopingTime--;

		if (!$this->lockData)
			$this->tempFields = $tempFields;
	}

	public function prepare()
	{
		$this->loopingTime++;
	}

	public function putField($field, $value)
	{
		$this->tempFields[$field] = $value;
	}
}
