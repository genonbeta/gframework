<?php

/*
 * SourceProvider.php
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

namespace genonbeta\provider;

class SourceProvider
{
	private static $providers = [];

	public static function getProvider($providerName)
	{
		if (self::providerExists($providerName))
			return self::$providers[$providerName];
	}

	public static function providerExists($providerName)
	{
		return isset(self::$providers[$providerName]);
	}

	public static function registerProvider(SourceProviderObject $provider)
	{
		if (self::providerExists($provider->getProviderName()))
			return false;

		self::$providers[$provider->getProviderName()] = $provider;

		return true;
	}

	public static function unregisterProvider(SourceProviderObject $provider)
	{
		if (!self::providerExists($provider->getProviderName()))
			return false;

		unset(self::$providers[$provider->getProviderName()]);

		return true;
	}
}
