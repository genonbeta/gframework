<?php

/*
 * AssetResource.php
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

use Configuration;
use genonbeta\io\File;
use genonbeta\provider\exception\ResourceNotFound;

class AssetResource
{
	private $resource;

	function __construct($resource)
	{
		if(!self::doesExist($resource))
			throw new ResourceNotFound("\"{$resource}\" does not exist.");

		$this->resource = $resource;
	}

	function getResourceStreamUri()
	{
		return Configuration::RESOURCE_PROTOCOL . "://" . $this->resource;
	}

	function getResourceFile()
	{
		$uri = $this->getResourceStreamUri();

		if($uri == false)
			return false;

		return new File($uri);
	}

	static function doesExist($resource)
	{
		return is_file(Configuration::RESOURCE_PROTOCOL . "://" . $resource);
	}

	static function openResource($resource)
	{
		if(!self::doesExist($resource))
			return false;

		return new AssetResource($resource);
	}

	static function getResourcePath($resource, $realpath = false)
	{
		if(!self::doesExist($resource))
			return false;

		$resource = Configuration::RESOURCE_PROTOCOL . "://" . $resource;

		$url = parse_url($resource);

		if($url == false)
			return false;

		$resource = ($realpath) ? realpath(Configuration::RESOURCE_PATH) . "/" . $url['host'] : Configuration::RESOURCE_PATH . "/" . $url['host'];

		if(isset($url['path']))
			$resource .= $url['path'];

		return $resource;
	}
}
