<?php

namespace genonbeta\provider;

use genonbeta\content\OutputWrapper;
use genonbeta\util\FlushArgument;

interface SourceProviderObject
{
	public function onRequest($requestIndex, FlushArgument $flushArgument);
	public function getProviderName();
}
