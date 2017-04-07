<?php

namespace genonbeta\view\provider;

use genonbeta\provider\SourceProviderObject;
use genonbeta\system\EnvironmentVariables;
use genonbeta\util\FlushArgument;

class EnvironmentVariablesProvider implements SourceProviderObject
{
	const PROVIDER_NAME = "envVar";

	public function getProviderName()
	{
		return self::PROVIDER_NAME;
	}

	public function onRequest($request, FlushArgument $flushArgument)
	{
		return EnvironmentVariables::get($request);
	}
}
