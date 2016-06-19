<?php

namespace genonbeta\util\model\data;

use genonbeta\controller\Callback;

class Boolean implements Callback
{
	function onCallback($data)
	{
		if (!is_bool($data))
			return false;

		return true;
	}
}
