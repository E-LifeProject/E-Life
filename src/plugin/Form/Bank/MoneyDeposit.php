<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;


class MoneyDeposit implements Form{
    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player, $data): void{
        $bank = Bank::getInstance();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();

        if($data === null){
            return;
        }

        if(!is_numeric($data[1]) || $data[1] === ""){
            $player->sendMessage("§a[個人通知] §7数字を入力してください");
            return;
        }


        if($money_instance->getMoney()>=intval($data[1])){
            $bank->addDepositBalance($this->name,intval($data[1]));
            $money_instance->reduceMoney(intval($data[1]));
            $player->sendMessage("§a[個人通知] §7お金を入金しました");
        }else{
            $player->sendMessage("§a[個人通知] §7入金金額が所持金を超えてる為入金出来ませんでした");
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'口座開設',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"預金残高:".Bank::getInstance()->getDepositBalance($this->name)."円\n入金したい金額を入力し、送信ボタンを押してください"
                ],
                [
                    'type'=>'input',
                    'text'=>'ご入金金額'
                ]
            ]
        ];
    }
}