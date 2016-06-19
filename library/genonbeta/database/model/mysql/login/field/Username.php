<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\Callback;

class Username implements Callback
{

	function onCallback($username)
	{
		return true;
	}

}
