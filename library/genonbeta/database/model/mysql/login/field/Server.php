<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\CallbackInterface;

class Server implements CallbackInterface
{

	function onCallback($server)
	{
		return true;
	}

}