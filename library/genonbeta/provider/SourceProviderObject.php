<?php

namespace genonbeta\provider;

use genonbeta\content\OutputWrapper;

interface SourceProviderObject
{
	public function onRequest($requestIndex);
	public function getProviderName();
}
