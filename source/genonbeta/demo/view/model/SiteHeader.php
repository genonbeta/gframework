<?php

namespace genonbeta\demo\view\model;

use genonbeta\controller\OutputController;
use genonbeta\lang\StringBuilder;
use genonbeta\view\Pattern;
use genonbeta\view\ViewInterface;

use genonbeta\demo\config\MainConfig;

class SiteHeader implements ViewInterface
{
	private $output;

	public function onCreate(array $methods)
	{
		$this->output = new OutputController();
		$pattern = Pattern::getPatternFromResource(MainConfig::PATTERN_INDEX_NAME, "site_header");

		$this->output->put("siteName", $pattern);
	}

	public function onFlush(array $backData)
	{
		return $this->output->onFlush($backData);
	}
}
