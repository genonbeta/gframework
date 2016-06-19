<?php

namespace genonbeta\database\model\mysql\login\field;

use genonbeta\controller\Callback;

class DbName implements Callback
{

	function onCallback($dbName)
	{
		return true;
	}

}
