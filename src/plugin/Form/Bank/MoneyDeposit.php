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

        if($money_instance->getMoney()>=intval($total)){
            $money_instance->reduceMoney(intval($total));
            $bank->addDepositBalance($this->name,intval($data[1]));
            $bank->addBankMoney($fee);
            $player->sendMessage("§a[個人通知] §7お金を入金しました");
        }else{
            $player->sendMessage("§a[個人通知] §7入金金額が所持金を超えてる為入金出来ませんでした");
        }
    }

    public function jsonSerialize(){
        $bank = Bank::getInstance();
        return[
            'type'=>'custom_form',
            'title'=>'ご入金',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"預金残高:".$bank->getDepositBalance($this->name)."円\nATM利用手数料:".$bank->checkFee()."円\n入金したい金額を入力し、送信ボタンを押してください"
                ],
                [
                    'type'=>'input',
                    'text'=>'ご入金金額'
                ]
            ]
        ];
    }
}