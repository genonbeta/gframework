<?php

namespace genonbeta\filemanager\language;

use genonbeta\support\LanguageInterface;
use genonbeta\support\Characters;
use genonbeta\support\Language;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\Resource;
use genonbeta\filemanager\config\MainConfig;

class Turkish implements LanguageInterface
{
	const TAG = "Turkish";

	function onInfo() : array
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

	function onLoading() : Language
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
