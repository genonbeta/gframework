<?php

/*
 * StackDataStore.php
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

class StackDataStore
{
	const TAG = "StackSataStore";

	protected $log;
	private $data;
	private $selfData = [];

	function __construct(StackDataStoreHelper $data)
	{
		$this->log = new Log(self::TAG);
		$this->data = $data;
	}

	function setField($field, $value)
	{
		if(!$this->data->fieldDefined($field))
		{
			$this->log->e("{$field} not defined");
			return false;
		}

		$this->selfData[$field] = $value;

		return false;
	}

	function checkAndDone()
	{
		return $this->data->checkAndDone($this->selfData);
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
