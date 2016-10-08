<?php

/*
 * DirectoryTraveller.php
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

namespace genonbeta\io;

use genonbeta\util\ErrorStack;
use genonbeta\util\Log;
use genonbeta\system\Intent;

class DirectoryTraveller
{
	const TAG = "DirectoryTraveller";

	const MODE_SAMETIME = 1;
	const MODE_SEPERATE_FILES = 2;

	const STATUS_DONE = 1;
	const STATUS_READY = 2;
	const STATUS_ERROR = 4;

	const ERROR_CANNOT_BE_READ = -1;

	private $directory;
	private $callback;
	private $errorStack;
	private $logger;
	private $completed = false;
	private $mode = self::MODE_SAMETIME;
	private $files = [];

	function __construct($directory, TravellerCallback $callback, $mode = self::MODE_SAMETIME)
	{
		$this->directory = new File(realpath($directory));
		$this->errorStack = new ErrorStack(self::TAG);
		$this->callback = $callback;
		$this->mode = $mode;
		$this->logger = new Log(self::TAG);
	}

	function setDirectory($directory)
	{
		$this->directory = $directory;
	}

	public function getStatus()
	{
		if ($this->completed === true)
			return self::STATUS_DONE;

		if ($this->directory->isDirectory() && $this->directory->isReadable())
			return self::STATUS_READY;

		return self::STATUS_ERROR;
	}

	public function start(Intent $intent = null)
	{
		if (!$this->traveller($this->directory->getPath(), $intent))
			return false;

		// this for helping system to seperate the files from diretories
		if ($this->mode == self::MODE_SEPERATE_FILES)
		{
			foreach ($this->files as $fileIns)
			{
				$this->callback->onCallback(pathinfo($fileIns->getPath()), $intent);
			}

			$this->files = [];
		}

		$this->callback->onTravelCompleted($intent);

		$this->logger->d($this->directory->getPath()." directory has been scanned");
		$this->completed = true;

		return true;
	}

	private function traveller($directory, Intent $intent = null)
	{
		if (!$dirOpen = opendir($directory))
			return false;

		$files = [];

		while ($fileName = readdir($dirOpen))
		{
			if ($fileName === "." || $fileName === "..")
				continue;

			$fileIns = new File($directory."/".$fileName);

			if ($fileIns->isDirectory() === true && $fileIns->isReadable() === true)
			{
				$this->traveller($fileIns->getPath(), $intent);
				$this->callback->onCallback(pathinfo($fileIns->getPath()), $intent);
			}
			elseif ($fileIns->isFile())
				if ($this->mode == self::MODE_SAMETIME)
				{
					$this->callback->onCallback(pathinfo($fileIns->getPath()), $intent);
				}
				elseif ($this->mode == self::MODE_SEPERATE_FILES)
				{
					$this->files[] = $fileIns;
					continue;
				}
			elseif ($fileIns->isReadable() === false)
				$this->errorStack->putError(self::TAG, self::ERROR_CANNOT_BE_READ);
		}

		closedir($dirOpen);
		return true;
	}
}
