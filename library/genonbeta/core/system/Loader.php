<?php

/*
 * Loader.php
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

namespace genonbeta\core\system;

use Configuration;

use genonbeta\content\URLAddress;
use genonbeta\content\OutputWrapper;
use genonbeta\system\Component;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\System;
use genonbeta\system\helper\CurrentManifest;
use genonbeta\util\FlushArgument;
use genonbeta\util\Log;
use genonbeta\view\ViewSkeleton;

abstract class Loader extends Component
{
	const TAG = "Loader";

	const SETTING_MAX_PATH_SIZE = "__loaderMaxPathSize";
	const VIEW_DEFAULT= "__default";
	const VIEW_ERROR = "__error";

	private $log;
	private $outputController;

	abstract protected function onCreate();
	abstract protected function onSkeletonLoaded(ViewSkeleton $skeleton);
	abstract protected function onDestroy();

	public function getClassId()
	{
		return __CLASS__;
	}

	function onLoad()
	{
		$this->onCreate();

		if(count(CurrentManifest::getViewIndex()) > 0)
		{
			$viewIndex = CurrentManifest::getViewIndex();
			$pathIndex = URLAddress::resolvePath();
			$pathCount = count($pathIndex);
			$leftPath = [];
			$currentView = $viewIndex[self::VIEW_DEFAULT];

			for ($wayNumber = $pathCount; $wayNumber > 0; $wayNumber--)
			{
				$try = implode("/", $pathIndex);

				if (isset($viewIndex[$try]) && class_exists($viewIndex[$try]))
				{
					$currentView = $viewIndex[$try];
					$leftPath = array_splice(URLAddress::resolvePath(), $wayNumber);
				}
				else
					array_pop($pathIndex);
			}

			if(class_exists($currentView))
			{
				$this->getLog()->d("{$currentView} view is loaded");
				$class = new $currentView();

				if(!$class instanceof ViewSkeleton)
					throw new \InvalidArgumentException("{$currentView} isn't instance of ViewSkeleton");

				EnvironmentVariables::define("currentSkeleton", $currentView);

				$class->onCreate($leftPath);
				$this->onSkeletonLoaded($class);
			}
			else
			{
				throw new \InvalidArgumentException("System couldn't find any view handling definition");
            }
		}
		else
        {
			throw new \InvalidArgumentException("No view was defined");
        }

		$this->onDestroy();
	}

	protected function getOutputWrapper()
	{
		if ($this->outputController == null)
			$this->outputController = new OutputWrapper();

		return $this->outputController;
	}

	protected function getLog()
	{
		if ($this->log == null)
			$this->log = new Log(self::TAG);

		return $this->log;
	}
}
