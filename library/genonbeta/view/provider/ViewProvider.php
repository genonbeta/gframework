<?php

namespace genonbeta\view\provider;

use genonbeta\content\PrintableObject;
use genonbeta\provider\ViewRegistry;
use genonbeta\provider\SourceProviderObject;
use genonbeta\util\FlushArgument;
use genonbeta\util\Log;
use genonbeta\util\PrintableUtils;
use genonbeta\view\ViewInterface;
use genonbeta\view\ViewPattern;

class ViewProvider implements SourceProviderObject
{
	const PROVIDER_NAME = "view";
	const TAG = "ViewProvider";

	public function getProviderName()
	{
		return self::PROVIDER_NAME;
	}

	public function onRequest($request, FlushArgument $flushArgument)
	{
		if (ViewRegistry::hasStaticView($request))
		{
			$staticView = ViewRegistry::getStaticView($request);
			$className = $staticView->getViewClass();

			$class = new $className;

			if ($class instanceof ViewPattern)
				return PrintableUtils::flush($class->drawAsAdapter($staticView->getItemList()), $flushArgument);
		}
		else
		{
			$className = "\\" . str_replace(".", "\\", $request);

			if (!class_exists($className))
			{
				Log::error(self::TAG, "View class not found: " . $className);
				return false;
			}

			$class = new $className;

			if ($class instanceof ViewInterface)
			{
				$class->onCreate($flushArgument->getFieldList());
				return PrintableUtils::flush($class, $flushArgument);
			}
		}

		Log::error(self::TAG, "Not a printable object");
		return false;
	}
}
