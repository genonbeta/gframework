<?php

/*
 * Turkish.php
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

namespace genonbeta\demo\language;

use genonbeta\support\LanguageInterface;
use genonbeta\support\Characters;
use genonbeta\support\Language;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\Resource;
use genonbeta\demo\config\MainConfig;

class Turkish implements LanguageInterface
{
	const TAG = "Turkish";



	function onInfo()
	{
		return array(
			"author" => "genonbeta",
			"language" => "Turkish",
			"id" => "tr",
			"location" => "Turkey",
			"timezone" => "Europe/Istanbul",
			"charset" => "utf-8"
		);
	}

	function onLoading()
	{
		$lang = new Language(ResourceManager::getResource(MainConfig::LANGUAGE_INDEX_NAME));
		$lang->loadFile(self::TAG);

		$ch = new Characters("Turkish");

		$ch->addMap("Ğ", "ğ", "g");
		$ch->addMap("Ç", "ç", "c");
		$ch->addMap("İ", "i", "i");
		$ch->addMap("I", "ı", "i");
		$ch->addMap("Ö", "ö", "o");
		$ch->addMap("Ş", "ş", "s");
		$ch->addMap("Ü", "ü", "u");

		return $lang;
	}
}
