<?php

namespace genonbeta\util\model\data;

use genonbeta\controller\CallbackInterface;

class Boolean implements CallbackInterface
{
	function onCallback($data)
	{
		if (!is_bool($data)) return false;
		return true;
	}
}
