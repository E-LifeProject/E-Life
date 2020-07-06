<?php

namespace bank\Form\Passbook;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;

class ReissuePassbook implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->fee = $main->setting->get("reissue-passbook-fee"); #手数料と銀行残高の1/3とかでもいいかも？
	}

	public function handleResponse(Player $player, $data):void{
		if ($data === null){
			return;
		}

		$money = $this->main->api->getInstance()->myMoney($player);

		if($data === true){
			if($money >= $this->fee){
				$player->sendForm(new Password($this->main, 1));
			} else {
				$player->sendMessage("§l§c>お金が足りません<");
			}
		}else{
			$player->sendMessage("§l§c>通帳再発行を中止しました。<");
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'modal',
            'title'=>'通帳再発行',
            'content'=>"通帳再発行には".$this->fee."Mかかります",
            'button1'=>'再発行します',
            'button2'=>'再発行しません'
        ];
	}
}