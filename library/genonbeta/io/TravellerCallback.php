<?php

namespace genonbeta\io;

use genonbeta\system\Intent;

interface TravellerCallback
{
	public function onCallback(array $currentStat, Intent $intent = null);
	public function onTravelCompleted(Intent $intent = null);
}
