<?php

namespace genonbeta\demo\language;

use genonbeta\support\Characters;
use genonbeta\support\Language;
use genonbeta\provider\ResourceManager;
use genonbeta\provider\Resource;

use genonbeta\demo\config\MainConfig;

class Turkish extends Language
{
	const TAG = "Turkish";

	function onInfo()
	{
		return array(
			self::INFO_AUTHOR => "genonbeta",
			self::INFO_NAME => "Turkish",
			self::INFO_CODENAME => "tr",
			self::INFO_LOCATION => "Turkey",
			self::INFO_LOCATION => "Europe/Istanbul",
			self::INFO_CHARSET => "utf-8"
		);
	}

	function onLoad()
	{
		$resource = ResourceManager::getResource(MainConfig::LANGUAGE_INDEX_NAME);

		if (!$resource instanceof Resource || !$resource->doesExist(self::TAG))
			throw new \Exception("Language file or resource entry can't be found");

		$this->loadFile($resource->findByName(self::TAG));

		$ch = new Characters("Turkish");

		$ch->addMap("Ğ", "ğ", "g");
		$ch->addMap("Ç", "ç", "c");
		$ch->addMap("İ", "i", "i");
		$ch->addMap("I", "ı", "i");
		$ch->addMap("Ö", "ö", "o");
		$ch->addMap("Ş", "ş", "s");
		$ch->addMap("Ü", "ü", "u");
	}
}
