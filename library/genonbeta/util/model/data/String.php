<?php

namespace genonbeta\util\model\data;

use genonbeta\controller\CallbackInterface;

class String implements CallbackInterface
{
	function onCallback($data)
	{
		if (is_string($data)) return true;
		return false;
	}
}
