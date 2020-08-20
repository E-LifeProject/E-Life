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

        if($data === null){
            return;
        }

        if(!is_numeric($data[2]) || $data[2] === ""){
            $player->sendMessage("§a[個人通知] §7数字を入力してください");
            return;
        }

        $bank = Bank::getInstance();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();
        $fee = $bank->checkFee();
        $total = intval($data[2]+$fee);

        if($bank->getDepositBalance($this->name)>=$total){
            $bank->reduceDepositBalance($this->name,$total);
            $money_instance->addMoney(intval($data[2]));
            $bank->addBankMoney(intval($fee));
            $player->sendMessage("§a[個人通知] §7お金を引き出しました");
        }else{
            $player->sendMessage("§a[個人通知] §7預金残高が足りない為、出金できませんでした");
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
                    'text'=>"預金残高:".$bank->getDepositBalance($this->name)."円\nATM利用手数料:".$bank->checkFee()."円\n出金したい金額を入力し、送信ボタンを押してください"
                ],
                [
                    'type'=>'label',
                    'text'=>"ATM利用手数料は預金残高から支払われる為、出金金額と利用手数料の合計値が預金残高よりも下回っていると出金できません"
                ],
                [
                    'type'=>'input',
                    'text'=>'出金金額',
                ]
            ]
        ];
    }
}