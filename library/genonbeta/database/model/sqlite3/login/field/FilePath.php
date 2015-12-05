<?php

namespace genonbeta\database\model\sqlite3\login\field;

use genonbeta\controller\CallbackInterface;

class FilePath implements CallbackInterface
{
	function onCallback($data)
	{
		return true;
	}
}
