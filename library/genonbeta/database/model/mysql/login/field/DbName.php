<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\CallbackInterface;

class DbName implements CallbackInterface
{

	function onCallback($dbName)
	{
		return true;
	}

}