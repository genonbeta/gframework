<?php

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
			$viewName = self::VIEW_DEFAULT;
			$currentView = $viewIndex[$viewName];
			$leftPath = [];

			for ($wayNumber = $pathCount; $wayNumber > 0; $wayNumber--)
			{
				$viewName = implode("/", $pathIndex);

				if (isset($viewIndex[$viewName]) && class_exists($viewIndex[$viewName]))
					$currentView = $viewIndex[$viewName];
				else
					$leftPath[] = array_pop($pathIndex);
			}

			$leftPath = array_reverse($leftPath);

			if(class_exists($currentView))
			{
				$this->getLog()->d("{$currentView} view is loaded");
				$class = new $currentView();

				if(!$class instanceof ViewSkeleton)
					throw new \InvalidArgumentException("{$currentView} isn't instance of ViewSkeleton");

				EnvironmentVariables::define("view", $currentView);
				EnvironmentVariables::define("viewAddress", URLAddress::getInstance($viewName));

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
