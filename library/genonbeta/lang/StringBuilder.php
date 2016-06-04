<?php

namespace genonbeta\lang;

use genonbeta\controller\FlushArgument;
use genonbeta\controller\RealtimeDataProcess;
use genonbeta\system\System;

class StringBuilder implements RealtimeDataProcess
{
	private $string = [];
	private $seperator = "\n";
	
	function __construct($defSeperator = "\n")
	{
		if(is_string($defSeperator))
			$this->seperator = $defSeperator;
	}
	
	function put($write)
	{
		if(is_string($write) || is_int($write) || ($write instanceof RealtimeDataProcess)) 
		{
			$this->string[] = $write;
			return true;
		}
		
		return false;
	}
	
	function remove($id)
	{
		if(!is_int($id) || !isset($this->string[$id]))
			return false;
		
		unset($this->string[$id]);

		return true;
	}
	
	function getString($bool = true)
	{
		$strAll = "";
		
		foreach($this->string as $num => $str)
		{
			if($num != 0)
				$strAll .= $this->seperator;

			$strAll .= ($str instanceof RealtimeDataProcess) ? $str->onFlush(array()) : $str;
		}
		
		if($bool)
			return new String($strAll);

		return $strAll;
	}
	
	public function onFlush(array $args)
	{
		$strAll = "";
		
		//$args = System::getService("Flusher")->send($args);
		
		foreach($this->string as $num => $str)
		{
			if($num != 0)
				$strAll .= $this->seperator;

			$strAll .= ($str instanceof RealtimeDataProcess) ? $str->onFlush($args) : $str;
		}
		
		$this->string = [];
	
		return $strAll;
	}
	
	function __toString()
	{
		return $this->getString(false);
	}
}
