<?php

namespace genonbeta\controller;

interface Callback
{
	// It can accept mixed data (Exp.: array, int, boolean, string)
	public function onCallback($data);
}
