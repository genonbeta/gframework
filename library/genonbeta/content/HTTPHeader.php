<?php

namespace genonbeta\content;

class HTTPHeader
{
    public $headerList = [];

    public function addHeader($key, $value)
    {
        $this->headerList[$key] = $value;
        return $this;
    }

    public function hasHeader($key)
    {
        return isset($this->headerList[$key]);
    }

    public function getHeader($key)
    {
        return !$this->hasHeader($key) ? false : $this->headerList[$key];
    }

    public function getHeaderList($key)
    {
        return $this->headerList;
    }
}
