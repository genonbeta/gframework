<?php

namespace genonbeta\util;

use genonbeta\content\PrintableObject;

class QueuedString implements PrintableObject
{
	private $data = [];
	private $seperator = null;

	public function getCount()
	{
		return count($this->data);
	}

	public function getString()
	{
		$output = null;

		foreach($this->data as $key => $string)
		{
			if ($this->seperator != null && $key > 0)
				$output .= $this->seperator;

			$output .= $string;
		}

		return $output;
	}

	public function put($string)
	{
		if (!is_string($string))
			return false;

		$this->data[] = $string;

		return true;
	}

	public function useSeperator($seperator)
	{
		$this->seperator = $seperator;
	}

	public function onFlush(FlushArgument $args)
	{
		return $this->getString();
	}

	public function __toString()
	{
		return $this->getString();
	}
}
