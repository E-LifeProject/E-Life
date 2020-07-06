<?php

namespace bank\Form\Money\Debt;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;

class DebtRules implements Form{
	public function __construct(Main $main, $index=0){
		$this->main = $main;
		$this->index = $index; # 0: ただの確認 1:貸す
	}

	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}
		if($this->index === 0){
			if($data){
				$player->sendMessage("§l§a>規則を守ってくださいね<");
			} else {
				$player->sendMessage("§l§c>同意しないとお貸しできません<");
			}
		} elseif($this->index === 1){
			if($data){
				$player->sendForm(new moneyAmount($this->main));
			} else {
				$player->sendMessage("§l§c>同意しないとお貸しできません<");
			}
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'modal',
            'title'=>'利用規則(借金)',
            'content'=>'~~~',
            'button1'=>'同意します',
            'button2'=>'同意しません'
        ];
	}
}