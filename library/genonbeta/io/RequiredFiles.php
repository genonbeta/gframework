<?php

namespace genonbeta\io;

use genonbeta\util\Log;
use genonbeta\util\HashMap;

class RequiredFiles
{
	private $logger;
	
	const TYPE_DIRECTORY = 1;
	const TYPE_FILE = 2;

	public function __construct(\string $pId)
	{
		$this->logger = new Log($pId);
	}

	public function request(\string $requestName, $type, \int $chmod = null, \string $index = null)
	{
		$fileInstance = new File($requestName);
		
		if ($fileInstance->isExists()) return true;
		
		if($type == self::TYPE_DIRECTORY) 
		{
			if($fileInstance->createNewDirectory($chmod))
			{
				$this->logger->i("{$requestName} created (folder)");
			}
			else
			{
				$this->logger->e("error, something went wrong {$requestName} (folder)");
			}
		}
		elseif($type == self::TYPE_FILE)
		{
			if($fileInstance->createNewFile())
			{
				$this->logger->i("{$requestName} created (file)");
				if ($index != null && $fileInstane->isWritable()) $fileInstance->putIndex($index);
			}
			else
			{
				$this->logger->e("error something went wrong {$requestName} (file)");
			}
		}
		else
		{
			$this->logger->e("the type you defined is not known {$type}");		
		}
	}
}