<?php

namespace genonbeta\system;

interface UniversalMessageFilterObject
{
	public function applyFilter($message);
	public function getType() : int;
}
