<?php

namespace bank\Form\Passbook;

use pocketmine\Player;
use pocketmine\form\Form;

use pocketmine\nbt\tag\StringTag;

use bank\Main;
use bank\BankItem;

class ChangePassword implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->item = new BankItem($main);
	}

	public function handleResponse(Player $player, $data):void{
		if ($data === null){
			return;
		}

		$passcard = $this->item->checkPasscard($player);

		$name = $player->getName();

		$old_pass = $data[1];
		$new_pass = $data[2];
		$new_pass2 = $data[3];
		$password = $this->main->getPassword($name);
		if($old_pass === $password){
			if($new_pass === $new_pass2){
				$player->getInventory()->removeItem($passcard);
				$passcard->setNamedTagEntry(new StringTag("password", $new_pass));
				$player->getInventory()->addItem($passcard);
				$this->main->setPassword($name, $new_pass);
				$this->main->saveConfig();
				$player->sendMessage("§l§a>パスワードを変更しました<");
			} else {
				$player->sendMessage("§l§c>新パスワードが合致しません<");
			}
		} else {
			$player->sendMessage("§l§c>旧パスワードが合致しません<");
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'パスワード変更',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"パスワードの変更を行います。"
                ],
                [
                    'type'=>'input',
                    'text'=>'旧パスワード',
                    'placeholder'=>'aiueo'
                ],
                [
                    'type'=>'input',
                    'text'=>'新パスワード',
                    'placeholder'=>'aiueo2'
                ],
                [
                    'type'=>'input',
                    'text'=>'新パスワード(再入力)',
                    'placeholder'=>'aiueo2'
                ]
            ]
        ];
	}
}