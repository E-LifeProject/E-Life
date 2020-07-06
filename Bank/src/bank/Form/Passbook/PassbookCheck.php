<?php

namespace bank\Form\Passbook;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;
use bank\DataBase\CreateAccount;

class PassbookCheck implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->fee = $main->setting->get("passbook-fee");
	}

	public function handleResponse(Player $player, $data):void{
		if ($data === null){
			return;
		}

		$money = $this->main->api->getInstance()->myMoney($player);

		if($data === true){
			if($money >= $this->fee){
				$player->sendForm(new Password($this->main));
			} else {
				$player->sendMessage("§l§c>お金が足りません<");
			}
		}else{
			$player->sendMessage("§l§c>通帳発行を中止しました。<");
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'modal',
            'title'=>'通帳発行',
            'content'=>'通帳発行には'.$this->fee.'Mかかります',
            'button1'=>'作成します',
            'button2'=>'作成しません'
        ];
	}
}