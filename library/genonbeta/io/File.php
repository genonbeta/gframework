<?php

/*
 * File.php
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

class File
{
	const TAG = "File";

	private $log;
	private $file = null;

	function __construct($fileName)
	{
		$this->log = new Log(self::TAG);
		$this->file = $fileName;
	}

	function createNewFile()
	{
		return touch($this->file);
	}

	function createNewDirectory($chmod = 0644)
	{
		return mkdir($this->file, $chmod);
	}

	function createDirectories()
	{
		return self::forceToMakeDirectory($this->file);
	}

	function putIndex($index)
	{
		return file_put_contents($this->file, $index);
	}

	function moveTo($newName)
	{
		if (rename($this->file, $newName))
			return new File($newName);

		return false;
	}

	function copyTo($copyTo)
	{
		if(copy($this->file, $copyTo))
			return new File($copyTo);

		return false;
	}

	function getIndex()
	{
		return file_get_contents($this->file);
	}

	function size()
	{
		return filesize($this->file);
	}

	function isWritable()
	{
		return is_writable($this->file);
	}

	function getPath()
	{
		return $this->file;
	}

	function deleteFile()
	{
		return unlink($this->file);
	}

	function deleteThisDirectory()
	{
		return self::deleteDirectory($this->file);
	}

	function copyThisDirectory($copyTo)
	{
		return self::copyDirectory($this->file, $copyTo);
	}

	function isReadable()
	{
		return is_readable($this->file);
	}

	function expressSize()
	{
		return self::sizeExpression($this->size());
	}

	function isFile()
	{
		return is_file($this->file);
	}

	function isDirectory()
	{
		return is_dir($this->file);
	}

	function doesExist()
	{
		return file_exists($this->file);
	}

	static function deleteDirectory($directory, $empty = false)
	{
		if(substr($directory,-1) == "/")
		{
			$directory = substr($directory,0,-1);
		}

		if(!file_exists($directory) || !is_dir($directory))
		{
			return false;
		}
		elseif(!is_readable($directory))
		{
			return false;
		}
		else
		{
			$directoryHandle = opendir($directory);

			while ($contents = readdir($directoryHandle))
			{
				if($contents != '.' && $contents != '..')
				{
					$path = $directory . "/" . $contents;
					if(is_dir($path))
					{
						self::deleteDirectory($path);
					}
					else
					{
						unlink($path);
					}
				}
			}

			closedir($directoryHandle);

			if($empty == false)
			{
				if(!rmdir($directory))
				{
					return false;
				}
			}

			return true;
		}
	}

	static function copyDirectory($directory, $dest, $first = false)
	{

		$first = ($first == false) ? realpath($dest) : realpath($first);

		if(substr($directory,-1) == "/")
			$directory = substr($directory,0,-1);

		if(substr($dest,-1) == "/")
			$dest = substr($dest,0,-1);

		if(!is_dir($dest))
			if(!mkdir($dest, 0777))
                return false;

		if(!file_exists($directory) || (!is_dir($directory) || !isset($dest)))
		{
			return false;
		}
		elseif(!is_readable($directory) || !is_readable($dest))
		{
			return false;
		}
		else
		{
			$directory = realpath($directory);
			$dest = realpath($dest);
			$directoryHandle = opendir($directory);

			while ($contents = readdir($directoryHandle))
			{
				if($contents != '.' && $contents != '..')
				{
					$path = $directory . "/" . $contents;
					$path2 = $dest . "/" . $contents;

					if(is_dir($path))
					{
						if($path == $first)
						{
							continue;
						}

						if(!is_dir($path2))
						{
							mkdir($path2, 0777);
						}

						self::copyDirectory($path, $path2, $first);
					}
					else
					{
						copy($path, $path2);
					}
				}
			}

			closedir($directoryHandle);
			return true;
		}
	}

	static function forceToMakeDirectory($directory)
	{
		$directory = explode('/', $directory);
		$dirNumber = count($directory);

		for($x = 0; $x < $dirNumber; $x++)
		{
			$currentDirPath = null;

			for($i = 0; $i <= $x; $i++)
				$currentDirPath .= ($i !== 0) ? '/'.$directory[$i] : $directory[$i];

			if(!is_dir($currentDirPath))
				if(!mkdir($currentDirPath, 0755))
					break;

			unset($currentDirPath);
		}

		return true;

	}

	static function sizeExpression($byte, $part = 1024, array $params = array())
	{
		if(!is_int($byte))
			$byte = 0;

		if(!is_int($part))
			$part = 1024;

		if(!is_array($params) || count($params) < 1)
			$params = array("B", "kB", "MB", "GB", "TB", "EB", "ZB", "YT");

		$c = min((int) log($byte, $part), count($params) - 1);

		return sprintf('%1.2f', $byte / pow($part, $c)).' '.$params[$c];
	}

	function __toString()
	{
		return $this->getPath();
	}

	function __destruct()
	{
	}
}
