<?php

namespace genonbeta\support;

use genonbeta\provider\ResourceManager;

interface LanguageInterface
{
	function onInfo() : array;
	function onLoading() : Language;
}
