<?php

declare(strict_types=1);

use pocketmine\item\Item;
use pocketmine\Player;

class OriginItemFactory
{
	/** @var array */
	private $origin_item = [];

	public function __construct() {
		$this->register([
		]);
	}

	private function register(array $items): void {
		foreach($items as $item) {
			$this->register($item);
		}
	}

	private function registerFor(OriginItem $item): void {
		if($this->isExist($item->getCustomName()))
			return;

		$this->origin_item[$item->getCustomName()] = $item;
	}

	private function isExist(string $origin_item_name): bool {
		return isset($this->origin_item[$origin_item_name]);
	}

	private function getOriginItemFor(string $oriin_item_name): OriginItem {
		return $this->origin_item[$oriin_item_name];
	}

	public function onUsed(Player $player, Item $item, $main): void {
		if($this->isExist($item->getCustomName()))
			$this->getOriginItemFor($item->getCustomName())->onUse($player, $main);
	}
}