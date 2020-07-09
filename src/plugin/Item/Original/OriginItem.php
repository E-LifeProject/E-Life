<?php

declare(strict_types=1);

namespace plugin\Item\Original;

use pocketmine\item\Item;
use pocketmine\Player;

abstract class OriginItem extends Item
{
	public function onUse(Player $player): void {
	}
}