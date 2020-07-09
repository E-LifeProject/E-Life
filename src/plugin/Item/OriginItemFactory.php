<?php

declare(strict_types=1);

namespace plugin\Item;

use plugin\Item\Original\MenuBook;
use plugin\Item\Original\OriginItem;
use pocketmine\item\Item;
use pocketmine\Player;

class OriginItemFactory
{
	/** @var array */
	private $origin_items;

	public function __construct() {
		$this->register([
			new MenuBook()
		]);
	}

	private function isExist(OriginItem $item): bool {
		return isset($this->origin_items[$item->getCustomName()]);
	}

	private function register(OriginItem ...$items): void {
		foreach($items as $item) {
			$this->registerFor($item);
		}
	}

	private function registerFor(OriginItem $item): void {
		if(!$this->isExist($item)) {
			$this->origin_items[$item->getCustomName()] = $item;
			Item::addCreativeItem($item);
		}
	}

	public function useFor(Player $player, Item $item): void {
		if(!($item instanceof OriginItem))
			return;

		if(!$this->isExist($item))
			return;

		$item->onUse($player);
	}
}