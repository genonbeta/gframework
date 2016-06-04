<?php

namespace genonbeta\view\filter;

use genonbeta\provider\SourceProvider;
use genonbeta\system\UniversalMessageFilterObject;
use genonbeta\util\Log;
use genonbeta\view\PatternFilter;

class ProviderFilter implements UniversalMessageFilterObject
{
	const TAG = __CLASS__;

	public function applyFilter($message)
	{
		return preg_replace_callback("#\@([a-zA-Z0-9_-]+)\/([a-zA-Z0-9_-]+)\;#", $this->getCallback(), $message);
	}

	public function getCallback()
	{
		return function($matches)
		{
			$providerName = $matches[1];
			$request = $matches[2];

			if (SourceProvider::providerExists($providerName))
			{
				$provider = SourceProvider::getProvider($providerName);
				return $provider->onRequest($request);
			}
			else
				Log::error(TAG, "Provider not found {$providerName}");

			return "";
		};
	}

	public function getType() : int
	{
		return PatternFilter::TYPE_CODE;
	}
}
