<?php

namespace genonbeta\content;

use genonbeta\util\FlushArgument;

interface PrintableObject
{
	public function onFlush(FlushArgument $args);
}
