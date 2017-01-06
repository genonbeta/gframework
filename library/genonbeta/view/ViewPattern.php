<?php

/*
 * ViewPattern.php
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
				$resultVariables[$key] = !isset($items[$key]) || empty($items[$key]) ? $def : $items[$key];

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

	public function getPattern()
	{
		return $this->pattern;
	}

	protected function getSkeleton()
	{
		return $this->skeleton;
	}
}
