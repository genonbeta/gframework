<?php

/*
 * Pattern.php
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

namespace genonbeta\view;

use genonbeta\content\PrintableObject;
use genonbeta\io\File;
use genonbeta\provider\AssetResource;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\resource\ResourceVariable;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\UniversalMessageFilter;
use genonbeta\view\PatternFilter;

class Pattern implements PrintableObject
{
	private $pattern;

	function __construct($pattern)
	{
		$this->pattern = $pattern;
	}

	public static function getPattenFromFile(File $file)
	{
		if (!$file->isFile() || !$file->isReadable())
			return false;

		return new Pattern($file->getIndex());
	}

	public static function getPatternFromResource($resourceName, $patternId)
	{
		if(!ResourceManager::resourceExists($resourceName))
			return false;

		$resource = ResourceManager::getResource($resourceName);

		if(!$resource->doesExist($patternId))
			return false;

		$resourcePath = $resource->findByName($patternId);
		$resourceFile = new File($resourcePath);

		return new Pattern($resourceFile->getIndex());
	}

	public static function getPatternAssetResource($resource)
	{
		if (!$file = AssetResource::openResource($resource)->getResourceFile())
			return false;

		return self::getPattenFromFile($file);
	}

	public function onFlush(array $args)
	{
		return $this->__toString();
	}

	public function __toString()
	{
		return UniversalMessageFilter::applyFilter($this->pattern, PatternFilter::TYPE_CODE);
	}
}
