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

	static function setCountFor(Player $player, ?int $count = null): void {
		$name = $player->getName();
		if($count === null)
			self::$count[$name] = ConfigBase::getFor(ConfigList::JOB_COUNT)->get($name);
		else
			self::$count[$name] = $count;
	}

	static function getCountFor(Player $player): int {
		return self::$count[$player->getName()];
	}
}