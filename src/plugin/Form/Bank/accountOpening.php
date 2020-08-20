<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;


class AccountOpening implements Form{

    public function handleResponse(Player $player, $data): void{
        $name = $player->getName();
        $bank = Bank::getInstance();
        $fee = $bank->checkAccountOpeningFee();
        $money_instance = new MoneyListener($name);

        if($data === null){
            return;
        }
        
        if($data[1] === true){
            if($money_instance->getMoney() >= $fee){
                $bank->accountOpening($player->getName());
                $money_instance->reduceMoney($fee);
                $bank->addBankMoney($fee);
                $player->sendMessage("§a[個人通知] §7口座を開設しました");
            }else{
                $player->sendMessage("§a[個人通知] §7口座開設手数料を支払う事ができませんでした");
            }
        }else{
            $player->sendMessage("§a[個人通知] §7口座開設を行いませんでした");
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'口座開設',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"口座開設手数料:".Bank::getInstance()->checkAccountOpeningFee()."円\n口座を開設する場合は口座開設ボタンにチェックを入れ、送信ボタンを押してください"
                ],
                [
                    'type'=>'toggle',
                    'text'=>'口座開設',
                    'default'=>false
                ]
            ]
        ];
    }
}