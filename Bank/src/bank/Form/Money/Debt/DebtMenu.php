<?php

namespace bank\Form\Money\Debt;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;

class DebtMenu implements Form{
	public function __construct(Main $main){
		$this->main = $main;
	}

	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}
		$name = $player->getname();
		switch($data){
			case 0:
				$player->sendForm(new DebtRules($this->main, 0));
				break;

			case 1:
				$player->sendForm(new DebtRules($this->main, 1));
				break;

			case 2:
				$debt = $this->main->getDebt($name);
				$bank_money = $this->main->getMoney($name);
				if($bank_money >= $debt){
					if($debt > 0){
						$player->sendForm(new returnMoney($this->main, $debt, $bank_money));
					} else {
						$player->sendMessage("§l§c>借金はありません<");
					}
				} else {
					$player->sendMessage("§l§c>現在の預金額では返せないのでお帰りください<");
				}
				break;
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'E-LifeBank',
            'content'=>'選択してください',
            'buttons'=>[
                [
                    'text'=>'利用規則',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/icon_bookshelf'
                    ]
                ],
                [
                    'text'=>'借金',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/move'
                    ]
                ],
                [
                    'text'=>'返済',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/move'
                    ]
                ]
            ]
        ];
	}
}