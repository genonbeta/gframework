<?php

/*
 * Debugger.php
 * 
 * Copyright 2016 Veli TASALI <veli.tasali@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

namespace genonbeta\demo\util;

use genonbeta\controller\Controller;
use genonbeta\system\Component;
use genonbeta\system\System;

class Debugger extends Component implements Controller
{
	function onRequest($args)
	{
		$endTime = microtime();

		echo '<div style="font-family: ubuntu mono; background: #b070b0; padding: 7px; font-size: 13px;">';
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
