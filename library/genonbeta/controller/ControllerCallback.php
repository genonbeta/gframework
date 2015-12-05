<?php

namespace genonbeta\controller;

interface ControllerCallback extends CallbackInterface
{
	public function onResult();
}
