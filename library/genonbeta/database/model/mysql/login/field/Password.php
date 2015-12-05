<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\CallbackInterface;

class Password implements CallbackInterface
{

	function onCallback($password)
	{
		return true;
	}

}