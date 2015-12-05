<?php

namespace genonbeta\database;

interface DbAdapterModel
{
	function query();
	function escape();
	function closeConnection();
	function getServerInfo();
	function getDbModelInfo();
}
