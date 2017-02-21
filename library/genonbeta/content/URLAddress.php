<?php

namespace genonbeta\content;

use genonbeta\util\FlushArgument;

class URLAddress implements PrintableObject
{
    private $workerPath;
    private $viewName;
    private $getValues = [];
    private $extraValues = null;

    public function __construct($workerPath)
    {
        $this->workerPath = $workerPath;
    }

    public static function getInstance($viewName, array $getValues = [], $extraValues = null)
    {
        return self::newInstance(G_WORKER_URL, $viewName, $getValues, $extraValues);
    }

    public static function newInstance($workerPath, $viewName, array $getValues = [], $extraValues = null)
    {
        $address = new URLAddress($workerPath);

        $address->setViewName($viewName);

        foreach ($getValues as $key => $value)
            $address->addGET($key, $value);

        $address->setExtraValues($extraValues);

        return $address;
    }

    public static function resolvePath()
	{
		$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : null;
		$result = [];

		foreach(explode("/", $path) as $id => $value)
		{
			if((!is_string($value) && !is_int($value)) || $value == null)
				continue;

			$result[] = $value;
		}

		return $result;
	}

    public function addGET($key, $value)
    {
        $this->getValues[$key] = $value;
        return $this;
    }

    public function hasGET($key)
    {
        return isset($this->getValues[$key]);
    }

    public function generate()
    {
        $getValues = false;

        foreach ($this->getValues as $key => $value)
        {
            $getValues .= !$getValues ? "?" : "&";
            $getValues .= urlencode($key) . "=" . urlencode($value);
        }

        return $this->workerPath . "/" . $this->viewName . $getValues . $this->extraValues;
    }

    public function getExtraValues()
    {
        return $this->extraValues;
    }

    public function getGET($key)
    {
        return $this->hasGET($key) ? $this->getValues[$key] : false;
    }

    public function getViewName()
    {
        return $this->viewName;
    }

    public function setExtraValues($extraValues)
    {
        $this->extraValues = $extraValues;
        return $this;
    }

    public function setViewName($viewName)
    {
        $this->viewName = $viewName;
        return $this;
    }

    public function onFlush(FlushArgument $flushArgument)
    {
        return $this->generate();
    }

    public function __toString()
    {
        return $this->generate();
    }
}
