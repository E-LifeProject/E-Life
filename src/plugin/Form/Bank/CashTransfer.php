<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;


class CashTransfer implements Form{

    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player, $data): void{

        if($data === null){
            return;
        }

        if(!is_numeric($data[3]) || $data[3] === ""){
            $player->sendMessage("§a[個人通知] §7数字を入力してください");
            return;
        }

        $bank = Bank::getInstance();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();
        $fee = $bank->checkFee();
        $transferFee = $bank->checkTransferFee();
        $total = intval($data[3] + $fee + $transferFee);

        /**
         * ATM手数料と振り込み手数料は
         * 銀行資金として充てられる
         */

        switch($data[1]){
            case 0:
                if($money >= $total){
                    if($bank->checkAccount($data[2])){
                        $money_instance->reduceMoney($total);
                        $bank->addDepositBalance($data[2],intval($data[3]));
                        $bank->addBankMoney(intval($fee + $transferFee));
                    }else{
                        $player->sendMessage("§a[個人通知] §7振込先の銀行口座が開設されていません");
                    }
                }else{
                    $player->sendMessage("§a[個人通知] §7振り込み金額より所持金が少ない為振り込みできません");
                }
            break;
            
            case 1:
                if($bank->checkAccount($this->name)){
                    if($bank->getDepositBalance($this->name) >= $total){
                        if($bank->checkAccount($data[2])){
                            $bank->reduceDepositBalance($this->name,$total);
                            $bank->addDepositBalance($data[2],intval($data[3]));
                            $bank->addBankMoney(intval($fee + $transferFee));
                        }else{
                            $player->sendMessage("§a[個人通知] §7相手先の銀行口座が開設されていません");
                        }
                    }else{
                        $player->sendMessage("§a[個人通知] §7預金残高が足りません");
                    }
                }else{
                    $player->sendMessage("§a[個人通知] §7銀行口座が開設されていません");
                }
            break;

        }
        
    }

    public function jsonSerialize(){
        $bank = Bank::getInstance();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();

        return[
            'type'=>'custom_form',
            'title'=>'お振込',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"預金残高:".$bank->getDepositBalance($this->name)."円\n所持金:".$money."円\n振り込み手数料:".$bank->checkTransferFee()."円\nATM利用手数料:".$bank->checkFee()."円\n振り込み方法を選択してください"
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'お振込方法',
                    'options'=>[
                        '所持金から',
                        '銀行口座から'
                    ],
                    'default'=>0
                ],
                [
                    'type'=>'input',
                    'text'=>'お振込先',
                    'placeholder'=>'ユーザー名'
                ],
                [
                    'type'=>'input',
                    'text'=>'お振込金額',
                    'placeholder'=>'振り込み金額'
                ],
                [
                    'type'=>'label',
                    'text'=>"ATM利用手数料と振り込み手数料は選択したお支払い方法元から支払われる為、振り込み金額と利用手数料の合計値が所持金や預金残高よりも下回っていると振り込みできません"
                ]
            ]
        ];
    }
}
