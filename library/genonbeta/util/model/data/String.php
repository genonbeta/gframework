<?php

namespace genonbeta\util\model\data;

use genonbeta\controller\Callback;

class String implements Callback
{
	function onCallback($data)
	{
		if (is_string($data))
			return true;

		return false;
	}
}
