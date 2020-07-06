<?php

namespace bank\Form\Money;

use pocketmine\Player;
use pocketmine\form\Form;

class CheckMoney implements Form{
	public function __construct($money, $debt){
		$this->money = $money;
        $this->debt = $debt;
	}

    public function handleResponse(Player $player,$data):void{
    	return;
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'銀行残高',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"銀行残高: ".$this->money."M\n借金金額: ".$this->debt."M"
                ]
            ]
        ];
    }
}