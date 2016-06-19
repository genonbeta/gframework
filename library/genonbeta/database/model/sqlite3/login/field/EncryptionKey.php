<?php

namespace genonbeta\database\model\sqlite3\login\field;

use genonbeta\controller\Callback;

class EncryptionKey implements Callback
{
	function onCallback($data)
	{
		return true;
	}
}
