<?php

namespace genonbeta\system;

interface UniversalMessageFilterObject
{
	public function applyFilter(string $message);
	public function getType() : int;
}
