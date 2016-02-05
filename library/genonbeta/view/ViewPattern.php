<?php

namespace genonbeta\view;

use genonbeta\database\Cursor;
use genonbeta\util\HashMap;
use genonbeta\controller\OutputController;
use genonbeta\controller\RealtimeDataProcess;
use genonbeta\view\html\Element;
use genonbeta\controller\RealtimeDataCreator;
use genonbeta\lang\StringBuilder;

abstract class ViewPattern
{
	const TAG = "ViewPattern";

	private $itemIds;
	private $pattern;
	private $skeleton;

	abstract function onCreatingPattern() : Pattern;
	abstract function onNotifingItems() : array;
	abstract function onControllingItems(array $items) : array;

	function __construct(ViewSkeleton $skeleton)
	{
		$this->itemIds = $this->onNotifingItems();
		$this->pattern = $this->onCreatingPattern();

		if(!$this->pattern instanceof Pattern)
			throw new \Exception("Pattern must be type of " . Pattern::class);
			
		$this->skeleton = $skeleton;
	}

	public function draw(array $items)
	{
		return $this->completeDrawer($items);
	}
	
	function drawAsAdapter(HashMap $map)
	{
		if($map->size() < 1) 
			return false;
	
		$cursor = new Cursor($map);

		if(!$cursor->moveToFirst()) 
			return false;

		$result = new OutputController;

		do
		{
			$result->putIndex(self::TAG, $this->draw($cursor->getIndex()));
		}
		while($cursor->moveToNext());

		return $result;
	}

	private function completeDrawer(array $items)
	{
		$resultVariables = array();
		$output = $this->pattern;
		$outputHolder = new StringBuilder();
		
		if(count($this->itemIds) > 0)
		{
			foreach($this->itemIds as $key => $def)
			{
				if(!isset($items[$key]) || empty($items[$key]))
					$resultVariables[$key] = $def;
				else
					$resultVariables[$key] = $items[$key];
			}
			
			$resultVariables = $this->onControllingItems($resultVariables);
			
			foreach($resultVariables as $key => $value)
			{
				if($value instanceof RealtimeDataProcess)
					$value = $value->onFlush(\genonbeta\controller\FlushArgument::getDefaultArguments());
						
				$output = str_replace('{$.'.$key.'}', $value, $output);
			}
		}

		$outputHolder->put($output);
		
		return $outputHolder;
	}
	
	protected function getSkeleton()
	{
		return $this->skeleton;
	}
}
