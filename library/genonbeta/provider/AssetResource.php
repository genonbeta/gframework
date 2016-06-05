<?php

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
