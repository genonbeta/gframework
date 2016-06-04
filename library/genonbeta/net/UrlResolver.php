<?php

namespace genonbeta\net;

class UrlResolver
{
	private $loader;
	private $paths = array();

	function __construct($phpLoader, array $skeleton)
	{
		$this->loader = $phpLoader;
		$this->paths = $skeleton;
	}

	public function getUri($skeleton, $abstractPath = null)
	{
		if (!$this->isDefined($skeleton))
			return false;
			
		$uri = $this->loader ."/".$skeleton;
		
		if ($abstractPath != null)
			$uri .= "/".$abstractPath;
		
		return $uri;
	}

	function isDefined($indexName)
	{
		return isset($this->paths[$indexName]);
	}
}
