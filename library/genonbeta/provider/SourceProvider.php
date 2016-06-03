<?php

namespace genonbeta\provider;

class SourceProvider
{
	private static $providers = [];

	public static function providerExists(string $providerName)
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

	public static function getProvider(string $providerName)
	{
		if (self::providerExists($providerName))
			return self::$providers[$providerName];
	}
}
