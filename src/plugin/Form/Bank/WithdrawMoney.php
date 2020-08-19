<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;


class withdrawMoney implements Form{
    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player, $data): void{
        $bank = Bank::getInstance();
        $fee = $bank->checkFee();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();

        if($data === null){
            return;
        }

        if(!is_numeric($data[1]) || $data[1] === ""){
            $player->sendMessage("§a[個人通知] §7数字を入力してください");
            return;
        }

        $total = $data[1]+$fee;

        if($bank->getDepositBalance($this->name)>=intval($total)){
            $bank->reduceDepositBalance($this->name,intval($total));
            $money_instance->addMoney(intval($data[1]));
            $bank->addBankMoney($fee);
            $player->sendMessage("§a[個人通知] §7お金を引き出しました");
        }else{
            $player->sendMessage("§a[個人通知] §7引き出し金額が預金残高を超えている為引き出しできませんでした");
        }
    }

    public function jsonSerialize(){
        $bank = Bank::getInstance();
        return[
            'type'=>'custom_form',
            'title'=>'お引き出し',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"預金残高:".$bank->getDepositBalance($this->name)."円\n ATM利用手数料:".$bank->checkFee()."円\n出金したい金額を入力し、送信ボタンを押してください"
                ],
                [
                    'type'=>'input',
                    'text'=>'出金金額',
                ]
            ]
        ];
    }
}