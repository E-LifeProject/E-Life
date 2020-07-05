<?php

declare(strict_types=1);

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Player;

class MainMenu extends OriginItem
{
	public function __construct() {
		parent::__construct(ItemIds::BOOK);

		$enchant = Enchantment::getEnchantment(26);
		$this->addEnchantment(new EnchantmentInstance($enchant, 5));

		$this->setCustomName("§l§aメインメニュー");
		$this->setLore(["本サーバーのメインメニューを開くことができるアイテム\n持っていたら無敵という訳ではない"]);

		Item::addCreativeItem($this);
	}

	public function onUse(Player $player, $main): void {
		//TODO; Form送る
	}
}