<?php

namespace bank\Form\Money\Debt;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;

class returnMoney implements Form{
	public function __construct(Main $main, $debt, $money){
		$this->main = $main;
		$this->setting = $main->setting;
		$this->debt = $debt;
		$this->money = $money;
	}

	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}

		$name = $player->getName();

		intval($data[1]);
		if($data[1] === ""){
			$player->sendMessage("§c入力されていません");

		}elseif(!is_numeric($data[1])){
			$player->sendMessage("§c数字にして下さい");

		}elseif($data[1] < 500){
			$player->sendMessage("§l§c>500M未満は対応していません<");

		}else{

			$return_money = $data[1];
			$result_debt = $this->debt - $return_money;
			if($result_debt >= 0){
				$this->main->reduceDebt($name, $return_money);
				$this->main->reduceMoney($name, $return_money);
				$player->sendMessage("§l§a>返金を確認しました。借金残高: ".$result_debt."M<");
			} else {
				$this->main->setDebt($name, 0);
				$result_debt *= -1;
				$this->main->reduceMoney($name, $return_money);
				$this->main->addMoney($name, $result_debt);
				$player->sendMessage("§l§a>全額返済を確認しました。余ったお金は銀行預金に戻ります<");
			}
			$this->config[$name]->save();
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'借金返済',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"あなたの借金額は".$this->debt."Mで、\n銀行残高は".$this->money."Mです。\n※500M未満の返済は認めていません"
                ],
                [
                    'type'=>'input',
                    'text'=>'返済額',
                    'placeholder'=>'50000'
                ]
            ]
        ];
	}
}