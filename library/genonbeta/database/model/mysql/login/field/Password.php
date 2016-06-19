<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\Callback;

class Password implements Callback
{

	function onCallback($password)
	{
		return true;
	}

}
