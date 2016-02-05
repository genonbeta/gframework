<?php

namespace genonbeta\provider;

use genonbeta\system\Intent;

abstract class Service
{
	protected abstract function onReceive(Intent $intent) : Intent;
	public abstract function getDefaultIntent() : Intent;
	
	public function send(Intent $intent) : Intent
	{
		return $this->onReceive($intent);
	}
}
