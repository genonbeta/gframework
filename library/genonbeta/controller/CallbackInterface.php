<?php

namespace genonbeta\controller;

interface CallbackInterface
{
	// It can accept mixed data (Exp.: array, int, boolean, string)
	public function onCallback($data);
}
