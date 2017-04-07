<?php

namespace genonbeta\view;

use genonbeta\content\OutputWrapper;
use genonbeta\content\PrintableObject;
use genonbeta\database\Cursor;
use genonbeta\util\HashMap;

abstract class ViewPattern implements DrawableView
{
	const TAG = "ViewPattern";

	private $itemIds;
	private $pattern;
	private $skeleton;

	abstract function onCreate();
	abstract function onNotify();
	abstract function onCheckingItems(array $items);

	function __construct(ViewSkeleton $skeleton = null)
	{
		$this->skeleton = $skeleton;

		$this->itemIds = $this->onNotify();
		$this->pattern = $this->onCreate();

		if(!$this->pattern instanceof Pattern)
			throw new \Exception("Pattern must be type of \\genonbeta\\view\Pattern");
	}

	private function completeDrawing(array $items)
	{
		$resultVariables = [];

		if(count($this->itemIds) > 0)
			foreach($this->itemIds as $key => $def)
			{
				$resultVariables[$key] = !isset($items[$key]) || empty($items[$key]) ? $def : $items[$key];
				unset($items[$key]);
			}

		if (count($items) > 0)
			$resultVariables = array_merge($resultVariables, $items);

		return (new FlushableViewPattern($this))->onCreate($resultVariables);
	}

	public function draw(array $items)
	{
		return $this->completeDrawing($items);
	}

	public function drawAsAdapter(HashMap $map)
	{
		if($map->size() < 1)
			return false;

		$cursor = new Cursor($map);

		if(!$cursor->moveToFirst())
			return false;

		$output = new OutputWrapper();

		do {
			$output->put(self::TAG, $this->draw($cursor->getIndex()));
		}
		while($cursor->moveToNext());

		return $output;
	}

	public function getItems()
	{
		return $this->itemIds;
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	protected function getSkeleton()
	{
		return $this->skeleton;
	}
}
