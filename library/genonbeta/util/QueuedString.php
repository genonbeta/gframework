<?php

/*
 * QueuedString.php
 * 
 * Copyright 2016 Veli TASALI <veli.tasali@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

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

	public function onFlush(array $args)
	{
		return $this->getString();
	}

	public function __toString()
	{
		return $this->getString();
	}
}
