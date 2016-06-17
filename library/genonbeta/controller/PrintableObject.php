<?php

namespace genonbeta\controller;

interface PrintableObject
{
	public function onFlush(array $args);
}
