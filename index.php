<?php

/*
* 'genonbeta framework' version 1.0
* This is an alfa version of gFramework
* © 2015 - Genonbeta Open Source Project
*/

define("G_LOAD_TIME", microtime()); // unix current microtime saved as constant
define("G_DOCUMENT_ROOT", dirname(__FILE__));
define("G_FRAMEWORK_ROOT", substr(G_DOCUMENT_ROOT, strlen($_SERVER['DOCUMENT_ROOT'])));
define("G_ADDRESS", $_SERVER["HTTP_HOST"] . G_FRAMEWORK_ROOT);
define("G_ADDRESS_FULL", $_SERVER["REQUEST_SCHEME"]  . "://". G_ADDRESS);

if(!file_exists("configuration.php"))
{
	die("No configuration file available");
}

include_once "configuration.php";

if(!class_exists("Configuration"))
{
	die("Configuration class was not found");
}

if(!file_exists($its = Configuration::LIBRARY_PATH."/genonbeta/system/System.".Configuration::CLASS_EXTENSION))
{
	die("<b>{$its}</b> was not found. System exited!.");
}

include_once Configuration::LIBRARY_PATH."/genonbeta/system/System.".Configuration::CLASS_EXTENSION;

if(!class_exists("genonbeta\system\System"))
{
	die("Main loader class was not found. System exited!");
}

use genonbeta\system\System;

try
{
	System::setup();
}
catch(Exception $e)
{
	die("<h1>Error on Start</h1><br />\n<b>Message:</b> ". $e->getMessage() ."\n<br /><b>Thrown in:</b> ". $e->getFile().":".$e->getLine());
}

$endTime = microtime();

echo '<div style="font-family: ubuntu mono; background: #b070b0; padding: 7px; margin: 1px; font-size: 13px;">';
echo "	<b>Memory Usage:</b> ".\genonbeta\io\File::sizeExpression(memory_get_usage());
echo "	<br><b>Real Memory Usage:</b> ".\genonbeta\io\File::sizeExpression(memory_get_usage(true));
echo "	<br><b>Load time:</b> " . ($endTime - G_LOAD_TIME) . "ms";
echo '</div>';