<?php

namespace genonbeta\view;

use genonbeta\controller\RealtimeDataProcess;

interface ViewInterface extends RealtimeDataProcess
{
	public function onCreate(array $items);
}
