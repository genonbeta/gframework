<?php

namespace genonbeta\filemanager\util;

use genonbeta\controller\Controller;
use genonbeta\system\Component;
use genonbeta\system\System;

class Debugger extends Component implements Controller
{
	function onRequest($args)
	{
		$endTime = microtime();

		echo '<div style="font-family: ubuntu mono; background: #b070b0; padding: 7px; margin: 1px; font-size: 13px;">';
		echo "	<b>Memory Usage:</b> ".\genonbeta\io\File::sizeExpression(memory_get_usage());
		echo "	<br /><b>Real Memory Usage:</b> ".\genonbeta\io\File::sizeExpression(memory_get_usage(true));
		echo "	<br /><b>Load time:</b> " . ($endTime - G_LOAD_TIME) . "ms";
		echo "	<br/ ><i>This information is created by ".$this->getClassId()."</i>";
		echo '</div>';
	}

	function onLoad()
	{
		System::getService("DestructionHook")->put($this);
	}

	function getClassId()
	{
		return __CLASS__;
	}
}
