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
		return preg_replace_callback("#\@([a-zA-Z0-9.,_:?\"-]+)\/([a-zA-Z0-9.,_:?\"-]+)\;#", $this->getCallback(), $message);
	}

	public function getCallback()
	{
		return function($matches)
		{
			$execute = true;
			$providerName = $matches[1];
			$request = $matches[2];

			if (count($condition = explode("?", $providerName)) > 1)
			{
				if ((count($equalityContidion = explode(":", $condition[0])) > 1 && EnvironmentVariables::get($equalityContidion[0]) == $equalityContidion[1]) || EnvironmentVariables::isDefined($condition[0]))
					$providerName = $condition[1];
				else
					$execute = false;
			}

			if (SourceProvider::providerExists($providerName))
			{
				$provider = SourceProvider::getProvider($providerName);
				return $provider->onRequest($request);
			}
			else
				Log::error(TAG, ($execute) ? "Provider not found {$providerName}" : "Execute condition is not matched (passed)");

			return "";
		};
	}

	public function getType()
	{
		return PatternFilter::TYPE_CODE;
	}
}
