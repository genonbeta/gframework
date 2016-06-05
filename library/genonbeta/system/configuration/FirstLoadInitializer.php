<?php

namespace genonbeta\system\configuration;

use Configuration;
use genonbeta\system\Component;
use genonbeta\provider\SourceProvider;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\UniversalMessageFilter;

class FirstLoadInitializer extends Component
{
	const TAG = "FirstLoadInitializer";

	protected function onLoad()
	{
		EnvironmentVariables::define("resourceAddress", Configuration::RESOURCE_PATH);
		EnvironmentVariables::define("documentRoot", G_DOCUMENT_ROOT);
		EnvironmentVariables::define("frameworkRoot", G_FRAMEWORK_ROOT);
		EnvironmentVariables::define("workerAddress", $_SERVER['SCRIPT_NAME']);

		UniversalMessageFilter::registerFilter(new \genonbeta\view\filter\ProviderFilter());
		SourceProvider::registerProvider(new \genonbeta\view\provider\EnvironmentVariablesProvider());
	}

	protected function getClassId()
	{
		return __CLASS__;
	}
}
