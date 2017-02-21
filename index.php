<?php

/*
 * index.php
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

if (version_compare(PHP_VERSION, '5.3.0', '<'))
	die("<html>\n<title>Oppps!!!</title>\n	We're sorry but your PHP version is not supported anymore. You're currently running on ".PHP_VERSION." <i>(< 5.3.0)</i>. Please get more information about this error <a href=\"https://github.com/genonbeta/gframework/issues\">https://github.com/genonbeta/gframework/issues</a> \n</html>");

define("G_LOAD_TIME", microtime()); // unix current microtime saved as constant
define("G_DOCUMENT_ROOT", dirname(__FILE__));
define("G_FRAMEWORK_ROOT", substr(G_DOCUMENT_ROOT, strlen($_SERVER['DOCUMENT_ROOT'])));
define("G_WORKER_URL", $_SERVER['SCRIPT_NAME']);
define("G_WORKER_PATH", dirname($_SERVER['SCRIPT_NAME']));

if(!file_exists("configuration.php"))
	die("No configuration file is available");

include_once "configuration.php";

if(!class_exists("Configuration"))
	die("Configuration class was not found");

$classSystem = realpath(Configuration::LIBRARY_PATH."/genonbeta/system/System.".Configuration::CLASS_EXTENSION);

if(!file_exists($classSystem))
	die("<b>{$classSystem}</b> was not found. Exited.");

include_once $classSystem;

if(!class_exists("genonbeta\system\System"))
	die("System class was not found. Exited.");

use genonbeta\system\System;

try {
	System::setup();
} catch(Exception $e) {
	echo "<html><title>Error on Start</title>";
	echo "<h1>Error on Start</h1>";
	echo "<br /><b>Message:</b> ". $e->getMessage();
	echo "<br /><b>Thrown in:</b> ". $e->getFile().":".$e->getLine();
	echo "<br /><br /><b>Stacktrace</b>";

	foreach($e->getTrace() as $trace)
	{
		if (!isset($trace['line']))
			$trace['line'] = "----";

		echo "<br />";
		echo "<i>".$trace['line']."</i> &nbsp; <b><span style=\"color: green;\">".$trace['function']."()</span></b> ".$trace['file'];
	}

	echo "</html>";
}
