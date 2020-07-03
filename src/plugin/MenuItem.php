<?php

namespace plugin;

use pocketmine\Player;
use pocketmine\item\Item;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;

class MenuItem{
	#ID 340:本
	public function getMenuItem(){
		$encha = Enchantment::getEnchantment(26);
		$menu_item = Item::get(340, 0, 1);
		$menu_item->addEnchantment(new EnchantmentInstance($encha, 5));

		$text = self::getMenu_ItemName();
		$menu_item->setCustomName($text);
		$menu_item->setLore(["本サーバーのメインメニューを開くことができるアイテム\n持っていたら無敵という訳ではない"]);
		return $menu_item;
	}

	public function getMenu_ItemName(){
		return "§l§aメインメニュー";
	}
}