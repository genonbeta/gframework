<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\CallbackInterface;

class Username implements CallbackInterface
{

	function onCallback($username)
	{
		return true;
	}

}