<?php

declare(strict_types=1);

namespace plugin\Config\Data;

use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\Player;

class JobCount
{
	/** @var array */
	private static $count;

	static function setCountFor(Player $player): void {
		$name = $player->getName();
		self::$count[$name] = ConfigBase::getFor(ConfigList::JOB_COUNT)->get($name);
	}
}