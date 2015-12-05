<?php

namespace genonbeta\system\configuration;

use genonbeta\system\EnvironmentVariables;
use genonbeta\system\Component;

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
	}

	protected function getClassId()
	{
		return __CLASS__;
	}
}