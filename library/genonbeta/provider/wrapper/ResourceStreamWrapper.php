<?php

namespace genonbeta\provider\wrapper;

use \Configuration;

class ResourceStreamWrapper
{
	private $uri;
	private $fResource;
	private $filePath = null;

	function stream_open($path, $mode, $options, &$opened_path)
	{
		$this->getUri($path);
		$this->fResource = fopen($this->getFilePath(), $mode);

		return is_resource($this->fResource);
	}

	function stream_metadata($path, $option, $value)
	{
		$this->getUri($path);

		if($option == STREAM_META_TOUCH)
			touch($this->getFilePath());

		return true;
	}

	function stream_stat()
	{
		return fstat($this->fResource);
	}

	function url_stat($path)
	{
		$this->getUri($path);
		return (is_file($this->getFilePath())) ? stat($this->getFilePath()) : false;
	}

	function stream_read($count)
	{
		return fread($this->fResource, $count);
	}

	function stream_write($data)
	{
		return fwrite($this->fResource, $data);
	}

	function stream_tell()
	{
		return ftell($this->fResource);
	}

	function stream_eof()
	{
		return feof($this->fResource);
	}

	function stream_seek($offset, $whence)
	{
		return fseek($this->fResource, $offset, $whence);
	}

	private function getUri($path)
	{
		$url = parse_url($path);

		if($url == false)
			return false;

		$this->uri = $url;

		return true;
	}

	private function getFilePath()
	{
		if($this->filePath == null)
		{
			$this->filePath = realpath(Configuration::RESOURCE_PATH) . "/" . $this->uri['host'];

			if(isset($this->uri['path']))
				$this->filePath .= $this->uri['path'];
		}

		return $this->filePath;
	}
}
