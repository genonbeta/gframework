<?php

/*
 * Resource.php
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

namespace genonbeta\provider;

use genonbeta\controller\Controller;

class Resource extends ResourceManager
{
	private $index = [];
	private $count = 0;

	function __construct($resourceName)
	{
		if(!self::resourceExists($resourceName))
			return false;

		$this->index = self::getResource($resourceName, false);
		$this->count = count($this->index['Data']);

		return true;
	}

	function getResourceId()
	{
		$data = $this->index;
		unset($data['Data']);

		return $data;
	}

	function getResourceFileType()
	{
		return $this->index['Type'];
	}

	function getResourceDir()
	{
		return $this->index['Directory'];
	}

	function findByName($name, $fullIndex = false)
	{
		if(!$this->doesExist($name))
			return false;

		if(!$fullIndex === true)
			return $this->index['Directory']."/".$this->index['Data'][$name]['basename'];

		return $this->index['Data'][$name];
	}

	function getIndex()
	{
		return $this->index['Data'];
	}

	function getCount()
	{
		return $this->count;
	}

	function doesExist($name)
	{
		return isset($this->index['Data'][$name]);
	}
}
