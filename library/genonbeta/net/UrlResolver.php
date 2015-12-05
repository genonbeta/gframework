<?php

namespace genonbeta\net;

class UrlResolver
{
	private $loader;
	private $paths = array();

	function __construct(\string $phpLoader, array $skeleton)
	{
		$this->loader = $phpLoader;
		$this->paths = $skeleton;
	}

	public function getUri(\string $skeleton, \string $abstractPath = null)
	{
		if (!$this->isDefined($skeleton))
			return false;
			
		$uri = $this->loader ."/".$skeleton;
		
		if ($abstractPath != null)
			$uri .= "/".$abstractPath;
		
		return $uri;
	}

	function isDefined(\string $indexName)
	{
		if(isset($this->paths[$indexName])) return true;
		return false;
	}
}