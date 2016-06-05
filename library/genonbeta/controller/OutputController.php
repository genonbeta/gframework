<?php

namespace genonbeta\controller;

use genonbeta\lang\StringBuilder;
use genonbeta\util\HashMap;
use genonbeta\system\System;
use genonbeta\controller\FlushArgument;

class OutputController implements RealtimeDataProcess
{
	private $output;
	private static $counter = 0;

	public function __construct()
	{
		$this->output = new HashMap();
	}

	public function put($outputTitle, RealTimeDataProcess $write)
	{
		$this->output->add(array($outputTitle, $write));
		return true;
	}

	public function onFlush(array $args = array())
	{
		//$args = System::getService("Flusher")->send($args);
		$flusher = new StringBuilder("\n".$args[FlushArgument::FLUSH_TABS]);

		foreach($this->output->getAll() as $output)
		{
			$flusher->put($output[1]);
		}

		$this->output->clear();

		return $flusher->onFlush($args);
	}
}
