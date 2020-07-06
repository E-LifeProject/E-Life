<?php

namespace bank\Form\Money;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;

class Deposit implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->fee = $this->main->setting->get("fee");
		$this->checkfee = $this->main->checkFee();
	}

	public function handleResponse(Player $player, $data):void{
		if ($data === null){
			return;
		}

		if($this->checkfee){
			$this->checkfee = $this->main->checkMember($player->getName());
		}

		if($data[1] === ""){
			$player->sendMessage("§c入力されていません");
		}elseif(!is_numeric($data[1])){

			$player->sendMessage("§c数字にして下さい");
		}else{

			$name = $player->getName();

			$money = $this->main->api->getInstance()->myMoney($player);
			$deposit_money = $data[1];

			if($deposit_money <= 0){
				$player->sendMessage("§l§c>0M以下は対応しかねます<");
				return;
			}

			if($money >= $deposit_money + $this->fee){
				$this->main->addMoney($name, $deposit_money);
				if ($this->checkfee){
					$this->main->api->getInstance()->reduceMoney($player, $deposit_money + $this->fee);
				} else {
					$this->main->api->getInstance()->reduceMoney($player, $deposit_money);					
				}
				$player->sendMessage("§l§a>".$deposit_money."Mを預入しました<");
				$this->main->config[$name]->save();
			} else {
				$player->sendMessage("§l§c入力された金額が所持金を超えています。");
			}
		}
	}

	public function jsonSerialize(){
		if($this->checkfee){
			$text = "預入する金額を入力してください。\n※手数料: ".$this->fee."M§a(E-Club会員なら無料)";
		} else {
			$text = "預入する金額を入力してください。";
		}
        return[
            'type'=>'custom_form',
            'title'=>'預入',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>$text
                ],
                [
                    'type'=>'input',
                    'text'=>'金額',
                    'placeholder'=>'1000'
                ]
            ]
        ];
	}
}