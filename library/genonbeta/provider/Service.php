<?php

namespace genonbeta\provider;

use genonbeta\system\Intent;

abstract class Service
{
	protected abstract function onReceive(Intent $intent);
	public abstract function getDefaultIntent();
	
	public function send(Intent $intent)
	{
		return $this->onReceive($intent);
	}
}
