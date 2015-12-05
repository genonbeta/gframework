<?php

namespace genonbeta\database;

abstract class TmpMemoryHolder
{
	static private $data;

	static function put($data, $index)
	{
		if(!self::checkDataOnSave($data, $index)) 
			return false;

		self::$data[$data] = $index;

		return true;
	}

	static function rewrite($data, $index)
	{
		if(!self::checkDataOnRewrite($data, $index)) 
			return false;

		self::$data[$data] = $index;

		return true;
	}

	static function get($data)
	{
		if(!self::isSaved($data)) 
			return false;

		return self::$data[$data];
	}

	static protected function checkDataOnSave($data, $index)
	{

		if(!self::checkData($data, $index))
			return false;
		
		if(isset(self::$data[$data]))
			return false;

		return true;
	}

	static protected function checkDataOnRewrite($data, $index)
	{
		if(!self::checkData($data, $index))
			return false;
		
		if(!isset(self::$data[$data]))
			return false;

		return true;
	}

	static function isSaved($data)
	{
		if(!is_string($data) || empty($data))
			return false;
		
		if(!isset(self::$data[$data])) 
			return false;

		return true; 
	}

	static protected function checkData($data, $index)
	{
		if(!is_string($data))
			return false;
		
		if(empty($index) || empty($data))
			return false;

		return true;
	}
}
