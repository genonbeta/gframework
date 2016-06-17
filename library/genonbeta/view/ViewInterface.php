<?php

namespace genonbeta\view;

use genonbeta\controller\PrintableObject;

interface ViewInterface extends PrintableObject
{
	public function onCreate(array $items);
}
