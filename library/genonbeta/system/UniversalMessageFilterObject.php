<?php

namespace genonbeta\system;

use genonbeta\util\FlushArgument;

interface UniversalMessageFilterObject
{
	public function applyFilter($message, FlushArgument $flushArgument);
	public function getType();
}
