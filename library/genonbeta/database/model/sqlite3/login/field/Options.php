<?php

namespace genonbeta\database\model\sqlite3\login\field;

use genonbeta\controller\CallbackInterface;

class Options implements CallbackInterface
{

	function onCallback($data)
	{
		return true;
	}

}
