<?php

namespace plugin\Form\Government;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\MoneyListener;


class CashReceipt implements Form{

    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }

        if(!is_numeric($data[2]) || $data[2] === ""){
            $player->sendMessage("§a[個人通知] §7数字を入力してください");
            return;
        }
        
        $instance = new MoneyListener($this->name);
        $total = $data[2];
        switch($data[1]){
            case 0:
                if(500000 - $instance->getMoney() < intval($data[2])){
                    $player->sendMessage("§a[個人通知] §7受け取り金額が多過ぎて受け取ることが出来ませんでした");
                }else{
                    $instance->reduceMoneyStorage(intval($data[2]));
                    $instance->addMoney(intval($data[2]));
                    $player->sendMessage("§a[個人通知] §7受け取りました");
                }
            break;

            case 1:
                $bank = Bank::getInstance();
                if($bank->checkAccount()){
                    $instance->reduceMoneyStorage(intval($data[2]));
                    $bank->addDepositBalance($player->getName(),intval($data[2]));
                }else{
                    $player->sendMessage("§a[個人通知] §7銀行口座が開設されていません");
                }
            break;
        }
    }


    public function jsonSerialize(){
        $instance = new MoneyListener($this->name);
        return[
            'type'=>'custom_form',
            'title'=>'保管金受け取り',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"保管金:".$instance->getMoneyStorage()."円\n保管手数料:2000円\n受け取り期限:".$instance->getMoneyStorageDate()
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'受け取り先',
                    'options'=>[
                        '所持金',
                        '銀行口座'
                    ]
                ],
                [
                    'type'=>'input',
                    'text'=>'受け取り金額'
                ]
            ]
        ];
    }
}