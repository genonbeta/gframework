<?php

namespace genonbeta\controller;

class RealtimeDataCreator implements RealtimeDataProcess
{
	private $proInstance;

	function __construct(RTDCreatorSupport $proInstance, array $items)
	{
		$this->proInstance = $proInstance;
		$this->proInstance->onAwaitedRequest($items);
	}

	public function onFlush(array $args)
	{
		return $this->proInstance->onFlush($args);
	}
}
