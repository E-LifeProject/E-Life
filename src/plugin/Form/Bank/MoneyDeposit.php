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

        if($money_instance->getMoney() >= $total){
            $money_instance->reduceMoney($total);
            $bank->addDepositBalance($this->name,intval($data[2]));
            $bank->addBankMoney(intval($fee));
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
                    'type'=>'label',
                    'text'=>"【注意事項】\nATM利用手数料は所持金から支払われる為、入金金額と利用手数料の合計値が所持金よりも下回っていると入金できません"
                ],
                [
                    'type'=>'input',
                    'text'=>'ご入金金額'
                ]
            ]
        ];
    }
}