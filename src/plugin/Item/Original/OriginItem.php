<?php

declare(strict_types=1);

use pocketmine\item\Item;
use pocketmine\Player;

abstract class OriginItem extends Item
{
	public function onUse(Player $player): void {
	}
}