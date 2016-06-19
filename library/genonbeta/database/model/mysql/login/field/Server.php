<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\Callback;

class Server implements Callback
{

	function onCallback($server)
	{
		return true;
	}

}
