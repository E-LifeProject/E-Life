<?php

namespace bank\Form\Money;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;
use bank\BankItem;

class Drawer implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->item = new BankItem($main);
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

		$passcard = $this->item->checkPasscard($player);
		$password = $passcard->getNamedTagEntry("password")->getValue();

		if($data[1] === ""){
			$player->sendMessage("§c入力されていません");

		}elseif(!is_numeric($data[1])){
			$player->sendMessage("§c数字にして下さい");

		}elseif($data[1] < 1000){
			$player->sendMessage("§c1000M未満の引出は対応していません。");

		}else{

			$name = $player->getName();
			$player_money = $this->main->api->getInstance()->myMoney($player);
			if($password === $data[2]){
				$money = $this->main->getMoney($name);
				if($money >= $data[1]){
					$this->main->reduceMoney($name, $data[1]);
					if($this->checkfee){
						$this->main->api->getInstance()->addMoney($player, $data[1] - $this->fee);
					} else {
						$this->main->api->getInstance()->addMoney($player, $data[1]);
					}
					$player->sendMessage("§l§a>".$data[1]."M引出しました<");
					$this->main->config[$name]->save();
				} else {
					$player->sendMessage("§l§c>記入された金額が銀行残高を超えています<");
				}
			} else {
				$player->sendMessage("§l§c>パスワードが違います<");
			}
		}
	}

	public function jsonSerialize(){
		if($this->checkfee){
			$text = "引出する金額を入力してください。\n最低引出金額は1000Mです。\n※手数料: ".$this->fee."M§a(E-Club会員なら無料)";
		} else {
			$text = "引出する金額を入力してください。\n最低引出金額は1000Mです。";
		}
        return[
            'type'=>'custom_form',
            'title'=>'引出',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>$text
                ],
                [
                    'type'=>'input',
                    'text'=>'金額',
                    'placeholder'=>'1000'
                ],
                [
                    'type'=>'input',
                    'text'=>'パスワード',
                    'placeholder'=>'aiueo'
                ]
            ]
        ];
	}
}