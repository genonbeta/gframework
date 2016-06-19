<?php

namespace genonbeta\view;

use genonbeta\content\PrintableObject;

interface ViewInterface extends PrintableObject
{
	public function onCreate(array $items);
}
