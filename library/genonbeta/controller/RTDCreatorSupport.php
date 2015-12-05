<?php

namespace genonbeta\controller;

interface RTDCreatorSupport extends RealtimeDataProcess
{
	public function onAwaitedRequest(array $items);
}
