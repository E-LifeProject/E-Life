<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;

class withdrawMoney implements Form{
    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player, $data): void{
        $bank = Bank::getInsatnce();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();

        if($data === null){
            return;
        }
        if($bank->getDepositBalance($this->name)>=$data[1]){
            $bank->reduceDepositBalance($this->name,$data[1]);
            $money_instance->addMoney($data[1]);
            $player->sendMessage("§a[個人通知] §7お金を引き出しました");
        }else{
            $player->sendMessage("§a[個人通知] §7引き出し金額が預金残高を超えている為引き出しできませんでした");
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'口座開設',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"預金残高:".Bank::getInstance()->getDepositBalance($this->name)."円\n出金したい金額を入力し、送信ボタンを押してください"
                ],
                [
                    'type'=>'input',
                    'text'=>'出金金額',
                    'default'=>1000,
                ]
            ]
        ];
    }
}