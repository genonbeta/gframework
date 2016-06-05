<?php

namespace genonbeta\database;

use genonbeta\util\StackDataStore;
use genonbeta\util\Log;

abstract class DbAdapter
{
	const TAG = "DbAdapter";

	private $log;
	private $loginInterface;

	abstract function onStartCommand(StackDataStore $login);
	abstract function getDbResource();

	function __construct(StackDataStore $login)
	{
		$this->log = new Log(self::TAG);

		if(!$login->checkAndDone())
		{
			$this->log->e("Server connection arguments not accepted");
			return false;
		}

		if(!$this->onStartCommand($login))
		{
			$this->log->e("Server connection failured");
			return false;
		}

		return true;
	}
}
