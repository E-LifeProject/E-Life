<?php

declare(strict_types=1);

namespace plugin\Item\Original;

use OriginItem;
use plugin\Form\MainMenu;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemIds;
use pocketmine\Player;

class MenuBook extends OriginItem
{
	public function __construct() {
		parent::__construct(ItemIds::BOOK);

		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(26), 5));
		$this->setCustomName(ItemList::MENU_BOOK_NAME);
		$this->setLore(ItemList::MENU_BOOK_LORE);
	}

	public function onUse(Player $player): void {
		$player->sendForm(new MainMenu());
	}
}