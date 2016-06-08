<?php

namespace genonbeta\view\provider;

use genonbeta\provider\SourceProviderObject;
use genonbeta\util\Log;
use genonbeta\view\ViewInterface;

class ViewProvider implements SourceProviderObject
{
	const PROVIDER_NAME = "view";
	const TAG = "ViewLoader";

	public function getProviderName()
	{
		return self::PROVIDER_NAME;
	}

	public function onRequest($request)
	{
		$className = "\\" . str_replace(".", "\\", $request);

		if (!class_exists($className))
		{
			Log::error(self::TAG, "View class not found. " . $className);
			return false;
		}

		$class = new $className;

		if (!$class instanceof ViewInterface)
		{
			Log::error(self::TAG, "Class must be instance of \\geonbeta\\view\\ViewInterface");
			return false;
		}

		$class->onCreate([]);

		return $class->onFlush([]);
	}
}
