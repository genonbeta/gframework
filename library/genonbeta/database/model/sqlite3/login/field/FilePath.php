<?php

namespace genonbeta\database\model\sqlite3\login\field;

use genonbeta\controller\Callback;

class FilePath implements Callback
{
	function onCallback($data)
	{
		return true;
	}
}
