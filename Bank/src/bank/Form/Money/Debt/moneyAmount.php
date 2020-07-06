<?php

namespace bank\Form\Money\Debt;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;

class moneyAmount implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->setting = $main->setting;
	}

	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}

		$name = $player->getName();


		if($data[1] === ""){
			$player->sendMessage("§c入力されていません");
		}elseif(!is_numeric($data[1])){

			$player->sendMessage("§c数字にして下さい");
		}else{

			$amount = $data[1];
			$now_debt = $this->main->getDebt($name);
			$sum = $amount + $now_debt;
			if($sum < $this->setting->get("debt-amount") + 1){
				if($sum > 1000){
					$this->main->addMoney($name, $amount);
					$this->main->addDebt($name, $amount);
					$this->main->config[$name]->save();
					$player->sendMessage("§l§a>またご利用お待ちしております<");
				} else {
					$player->sendMessage("§l§c>最低金額を下回っています<");
				}
			} else {
				$player->sendMessage("§l§c>最大金額を超えています<");
			}
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'借金',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"借りられる最大金額は".$this->setting->get("debt-amount")."Mで、\n最低金額は1000Mです。\n※反映には数日かかります"
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