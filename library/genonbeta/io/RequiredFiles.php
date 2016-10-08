<?php

/*
 * RequiredFiles.php
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

use genonbeta\util\Log;
use genonbeta\util\HashMap;

class RequiredFiles
{
	private $logger;

	const TYPE_DIRECTORY = 1;
	const TYPE_FILE = 2;

	public function __construct($pId)
	{
		$this->logger = new Log($pId);
	}

	public function request($requestName, $type, $chmod = null, $index = null)
	{
		$fileInstance = new File($requestName);

		if ($fileInstance->doesExist())
			return true;

		if($type == self::TYPE_DIRECTORY)
		{
			if($fileInstance->createNewDirectory($chmod))
			{
				$this->logger->i("{$requestName} created (folder)");
			}
			else
			{
				$this->logger->e("error, something went wrong {$requestName} (folder)");
			}
		}
		elseif($type == self::TYPE_FILE)
		{
			if($fileInstance->createNewFile())
			{
				$this->logger->i("{$requestName} created (file)");

				if ($index != null && $fileInstane->isWritable())
					$fileInstance->putIndex($index);
			}
			else
			{
				$this->logger->e("error something went wrong {$requestName} (file)");
			}
		}
		else
		{
			$this->logger->e("the type you defined is not known {$type}");
		}
	}
}
