<?php

namespace genonbeta\core\system;

use Configuration;
use genonbeta\controller\FlushArgument;
use genonbeta\controller\OutputController;
use genonbeta\system\Component;
use genonbeta\system\EnvironmentVariables;
use genonbeta\system\System;
use genonbeta\util\Log;
use genonbeta\util\NativeUrl;
use genonbeta\view\ViewSkeleton;

abstract class Loader extends Component
{
	const TAG = "Loader";

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

		if(count(System::getLoadedManifest()['system']['viewSkeleton']) > 0)
		{
			$skeleton = System::getLoadedManifest()['system']['viewSkeleton'];
			$path = NativeUrl::pathResolver();
			$pathCount = count($path);
			$leftValues = [];
			$selectedSkeleton = $skeleton[ViewSkeleton::DEFAULT_SKELETON];
			$wayHolder = null;

			foreach ($path as $wayNumber => $currentPath)
			{
				($wayNumber === 0) ? $wayHolder = $currentPath : $wayHolder .= "/".$currentPath;

				if (isset($skeleton[$wayHolder]))
				{
					if (class_exists($skeleton[$wayHolder]))
					{
						$selectedSkeleton = $skeleton[$wayHolder];
						$leftValues = array_splice($path, $wayNumber);
					}
				}
				else
					$this->getLogger()->d("{$wayHolder} path was not found. Previously founded ViewSkeleton.{$selectedSkeleton} is being loaded");

				if($wayNumber > $pathCount)
					break;
			}

			if(class_exists($selectedSkeleton))
			{
				$this->getLogger()->d("ViewSkeleton.{$selectedSkeleton} is loaded");
				$class = new $selectedSkeleton();

				if(!$class instanceof ViewSkeleton)
					throw new \InvalidArgumentException("{$defClass} isn't instance of ViewSkeleton");

				$class->onCreate($leftValues);
				$this->onSkeletonLoaded($class);
			}
			else
			{
				throw new \InvalidArgumentException("System couldn't find any ViewSkeleton class");
            }
		}
		else
        {
			throw new \InvalidArgumentException("No ViewSkeleton.class was defined.");
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
