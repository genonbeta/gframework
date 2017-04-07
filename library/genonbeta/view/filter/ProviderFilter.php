<?php

namespace genonbeta\view\filter;

use genonbeta\provider\SourceProvider;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\UniversalMessageFilterObject;
use genonbeta\util\Log;
use genonbeta\util\FlushArgument;
use genonbeta\view\PatternFilter;

class ProviderFilter implements UniversalMessageFilterObject
{
	const TAG = __CLASS__;

	public function applyFilter($message, FlushArgument $flushArgument)
	{
		$flushArgument->preventItemRemoving(true);

		$message = preg_replace_callback("#\@([a-zA-Z0-9.,_:?\"-]+)\/([a-zA-Z0-9.,_:?\"-]+)\;#", $this->getCallback($flushArgument), $message);
		$message = preg_replace_callback("#if\(\"(.*?)\"(\!|\=)(\=|\>|\<)\"(.*?)\"\)(.*?)\@fi;#", $this->getConditionCallback(), $message);

		$flushArgument->preventItemRemoving(false);

		return $message;
	}

	public function getConditionCallback()
	{
		return function($matches)
		{
			$value1 = $matches[1];
			$value2 = $matches[4];
			$condition = $matches[3];
			$trueCondition = $matches[2] == "=";

			$result = ($condition == ">" && $value1 > $value2)
					|| ($condition == "<" && $value1 < $value2)
					|| ($condition == "=" && $value1 == $value2);

			return $result == $trueCondition ? $matches[5] : "";
		};
	}

	public function getCallback(FlushArgument $flushArgument)
	{
		return function($matches) use ($flushArgument)
		{
			$providerName = $matches[1];
			$request = $matches[2];

			if (SourceProvider::providerExists($providerName))
			{
				$provider = SourceProvider::getProvider($providerName);
				return $provider->onRequest($request, $flushArgument);
			}
			else
				Log::error(TAG, "Provider not found {$providerName}");

			return "";
		};
	}

	public function getType()
	{
		return PatternFilter::TYPE_TEMPLATE;
	}
}
