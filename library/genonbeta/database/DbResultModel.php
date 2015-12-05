<?php

namespace genonbeta\database;

interface DbResultModel
{
	function fetchArray();
	function numRows();
	function result();
	function numField();
	function getCursor();
	function getHashMap();
}