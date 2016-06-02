<?php

namespace genonbeta\view\provider;

use genonbeta\provider\SourceProviderObject;
use genonbeta\system\EnvironmentVariables;

class EnvironmentVariablesProvider implements SourceProviderObject
{
	const PROVIDER_NAME = "envVar";

	public function getProviderName() : string
	{
		return self::PROVIDER_NAME;
	}

	public function onRequest(string $request) : string
	{
		return EnvironmentVariables::get($request);
	}
}
