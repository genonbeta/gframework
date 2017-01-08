<?php

/*
 * FlushArgument.php
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

class FlushArgument
{
	private $loopingTime = 0;
	private $tempFields = [];
	private $lockData = false;

	public static function flush(PrintableObject $printable, FlushArgument $flushArgument)
	{
		$flushArgument->prepare();
		$output = $printable->onFlush($flushArgument);
		$flushArgument->loop();

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

	public function loop()
	{
		$this->loopingTime--;

		if (!$this->lockData)
			$this->tempFields = [];
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
