<?php

namespace genonbeta\database\model\sqlite3\login\field;

use genonbeta\controller\CallbackInterface;

class EncryptionKey implements CallbackInterface
{
	function onCallback($data)
	{
		return true;
	}
}
