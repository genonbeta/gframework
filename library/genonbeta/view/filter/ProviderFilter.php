<?php

/*
 * ProviderFilter.php
 *
 * Copyright 2016 Veli TASALI <veli.tasali@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

namespace genonbeta\view\filter;

use genonbeta\provider\SourceProvider;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\UniversalMessageFilterObject;
use genonbeta\util\Log;
use genonbeta\view\PatternFilter;

class ProviderFilter implements UniversalMessageFilterObject
{
	const TAG = __CLASS__;

	public function applyFilter($message)
	{
		$message = preg_replace_callback("#\@([a-zA-Z0-9.,_:?\"-]+)\/([a-zA-Z0-9.,_:?\"-]+)\;#", $this->getCallback(), $message);
		return preg_replace_callback("#\@if\(\"([\w\W]+|)\"(\!|\=)(\=|\>|\<)()\"([\w\W]+|)\"\)([\s\S]+)\@endif;#", $this->getConditionCallback(), $message);
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

			return $result == $trueCondition ? $matches[6] : "";
		};
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

	public function getType()
	{
		return PatternFilter::TYPE_CODE;
	}
}
