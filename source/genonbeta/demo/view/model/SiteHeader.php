<?php

namespace genonbeta\demo\view\model;

use genonbeta\content\OutputWrapper;
use genonbeta\lang\StringBuilder;
use genonbeta\util\FlushArgument;
use genonbeta\util\PrintableUtils;
use genonbeta\view\Pattern;
use genonbeta\view\ViewInterface;

use genonbeta\demo\config\MainConfig;

class SiteHeader implements ViewInterface
{
	private $output;

	public function onCreate(array $methods)
	{
		$this->output = new OutputWrapper();
		$pattern = Pattern::getPatternFromResource(MainConfig::PATTERN_INDEX_NAME, "site_header");

		$this->output->put("siteName", $pattern);
	}

	public function onFlush(FlushArgument $flushArgument)
	{
		return PrintableUtils::flush($this->output, $flushArgument);
	}
}
