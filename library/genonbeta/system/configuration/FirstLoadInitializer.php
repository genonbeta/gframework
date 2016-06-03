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
		EnvironmentVariables::define("resourceAddress", G_ADDRESS_FULL . "/" . \Configuration::RESOURCE_PATH);
		EnvironmentVariables::define("serverAddress", G_ADDRESS);
		EnvironmentVariables::define("serverAddressFull", G_ADDRESS_FULL);
		EnvironmentVariables::define("documentRoot", G_DOCUMENT_ROOT);
		EnvironmentVariables::define("frameworkRoot", G_FRAMEWORK_ROOT);
		EnvironmentVariables::define("workerAddress", G_ADDRESS_FULL."/".Configuration::WORKER_URL);

		UniversalMessageFilter::registerFilter(new \genonbeta\view\filter\ProviderFilter());
		SourceProvider::registerProvider(new \genonbeta\view\provider\EnvironmentVariablesProvider());
	}

	protected function getClassId()
	{
		return __CLASS__;
	}
}
