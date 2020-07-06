<?php

namespace bank\Form\Money\Debt\black;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;
use bank\BankItem;

class DebtCheck implements Form{
	public function __construct(Main $main){
		$this->main = $main;
		$this->item = new BankItem($main);
	}

	public function handleResponse(Player $player, $data):void{
		if ($data === null){
			return;
		}
        if($data){
            return;
        } else {
            return;
        }
	}

	public function jsonSerialize(){
        return[
            'type'=>'modal',
            'title'=>'利用規則(闇金)',
            'content'=>'~~~',
            'button1'=>'同意します',
            'button2'=>'同意しません'
        ];
	}
}