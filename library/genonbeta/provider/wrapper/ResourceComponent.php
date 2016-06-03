<?php

namespace genonbeta\provider\wrapper;

use \Configuration;
use \genonbeta\system\Component;

final class ResourceComponent extends Component
{
	protected function onLoad()
	{
		stream_wrapper_register(Configuration::RESOURCE_PROTOCOL, "\\genonbeta\\provider\\wrapper\\ResourceStreamWrapper");
	}

	protected function getClassId()
	{
		return __CLASS__;
	}
}
