<?php

namespace genonbeta\core\system;

use Configuration;
use genonbeta\controller\FlushArgument;
use genonbeta\controller\OutputController;
use genonbeta\system\Component;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\System;
use genonbeta\system\helper\CurrentManifest;
use genonbeta\util\Log;
use genonbeta\util\NativeUrl;
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
			$pathIndex = NativeUrl::pathResolver();
			$pathCount = count($pathIndex);
			$leftPath = [];
			$currentView = $viewIndex[self::VIEW_DEFAULT];

			for ($wayNumber = $pathCount; $wayNumber > 0; $wayNumber--)
			{
				$try = implode("/", $pathIndex);

				if (isset($viewIndex[$try]) && class_exists($viewIndex[$try]))
				{
					$currentView = $viewIndex[$try];
					$leftPath = array_splice(NativeUrl::pathResolver(), $wayNumber);
				}
				else
					array_pop($pathIndex);
			}

			if(class_exists($currentView))
			{
				$this->getLogger()->d("{$currentView} view is loaded");
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

	protected function getOutputController()
	{
		if ($this->outputController == null)
			$this->outputController = new OutputController();

		return $this->outputController;
	}

	protected function getLogger()
	{
		if ($this->log == null)
			$this->log = new Log(self::TAG);

		return $this->log;
	}
}
