<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;


class BalanceInquiry implements Form{

    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player, $data): void{
        if($data === null){
            return;
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'残高確認',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"預金残高:".Bank::getInstance()->getDepositBalance($this->name)."円"
                ]
            ]
        ];
    }
}