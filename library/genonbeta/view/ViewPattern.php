<?php

namespace genonbeta\view;

use genonbeta\content\OutputWrapper;
use genonbeta\content\PrintableObject;
use genonbeta\database\Cursor;
use genonbeta\lang\StringBuilder;
use genonbeta\util\HashMap;

abstract class ViewPattern
{
	const TAG = "ViewPattern";

	private $itemIds;
	private $pattern;
	private $skeleton;

	abstract function onCreatingPattern();
	abstract function onNotifingItems();
	abstract function onControllingItems(array $items);

	function __construct(ViewSkeleton $skeleton)
	{
		$this->skeleton = $skeleton;

		$this->itemIds = $this->onNotifingItems();
		$this->pattern = $this->onCreatingPattern();

		if(!$this->pattern instanceof Pattern)
			throw new \Exception("Pattern must be type of \\genonbeta\\view\Pattern");
	}

	public function draw(array $items)
	{
		return $this->completeDrawer($items);
	}

	public function drawAsAdapter(HashMap $map)
	{
		if($map->size() < 1)
			return false;

		$cursor = new Cursor($map);

		if(!$cursor->moveToFirst())
			return false;

		$result = new OutputWrapper;

		do
		{
			$result->put(self::TAG, $this->draw($cursor->getIndex()));
		}
		while($cursor->moveToNext());

		return $result;
	}

	private function completeDrawer(array $items)
	{
		$resultVariables = [];
		$output = $this->pattern;

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
				if($value instanceof PrintableObject)
					$value = $value->onFlush(\genonbeta\util\FlushArgument::getDefaultArguments());

				$output = str_replace('{$.'.$key.'}', $value, $output);
			}
		}

		return $output;
	}

	protected function getSkeleton()
	{
		return $this->skeleton;
	}
}
