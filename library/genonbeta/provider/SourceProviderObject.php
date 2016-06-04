<?php

namespace genonbeta\provider;

use genonbeta\controller\OutputController;

interface SourceProviderObject
{
	public function onRequest($requestIndex);
	public function getProviderName();
}
