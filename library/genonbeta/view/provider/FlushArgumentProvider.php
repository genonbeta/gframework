<?php

namespace genonbeta\view\provider;

use genonbeta\content\PrintableObject;
use genonbeta\provider\SourceProviderObject;
use genonbeta\util\Log;
use genonbeta\util\FlushArgument;
use genonbeta\util\PrintableUtils;
use genonbeta\view\ViewInterface;

class FlushArgumentProvider implements SourceProviderObject
{
	const PROVIDER_NAME = "args";
	const TAG = "FlushArgumentProvider";

	public function getProviderName()
	{
		return self::PROVIDER_NAME;
	}

	public function onRequest($request, FlushArgument $flushArgument)
	{
        if (!$flushArgument->hasField($request))
            Log::error(self::TAG, "Field not found: " . $request);

		return $flushArgument->getField($request);
	}
}
