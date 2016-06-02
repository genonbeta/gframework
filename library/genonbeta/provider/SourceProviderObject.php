<?php

namespace genonbeta\provider;

use genonbeta\controller\OutputController;

interface SourceProviderObject
{
	public function onRequest(String $requestIndex) : string;
	public function getProviderName() : string;
}
