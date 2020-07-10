<?php

namespace bank;

use pocketmine\Player;
use pocketmine\item\item;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;

use pocketmine\nbt\tag\StringTag;

class BankItem{
	public function __construct(Main $main){
		$this->main = $main;
	}

/* ItemId: 339->紙  340->本 */

	public function createPassCard($name, $password){
		$encha = Enchantment::getEnchantment(26);
		$passcard = Item::get(339, 0, 1);
		$passcard->addEnchantment(new EnchantmentInstance($encha, 5));

		$text = self::getPasscardName($name);
		$passcard->setCustomName($text);
		$passcard->setLore(["いわゆる通帳\nこれを持っていないと銀行の利用はほぼできない。\nなしたら..."]);
		$passcard->setNamedTagEntry(new StringTag("password", $password));
		$passcard->setNamedTagEntry(new StringTag("money", 0));
		return $passcard;
	}

	public function getPasscardName($name){
		return "§o§l§a通帳 §r§l§e(".$name.")";
	}

	public function checkPasscard($player){
		$contents = $player->getInventory()->getContents();
		foreach ($contents as $content){
			$name = $content->getCustomName();
			$passcard_name = self::getPasscardName($player->getName());
			if($name === $passcard_name){
				return $content;
			}
		}
		return null;
	}

	/*public function createDebitcard($name, $password){
		$encha = Enchantment::getEnchantment(26);
		$passcard = Item::get(340, 0, 1);
		$passcard->addEnchantment(new EnchantmentInstance($encha, 5));

		$passcard->setCustomName("§o§l§a通帳 §r§l§e(".$name.")")
		$passcard->setNamedTagEntry(new StringTag("password", $password));
		return $passcard;
	}*/
}