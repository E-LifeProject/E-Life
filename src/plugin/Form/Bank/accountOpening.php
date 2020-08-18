<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;

class accountOpening implements Form{
    public function handleResponse(Player $player, $data): void{
        $name = $player->getName();
        $bank = Bank::getInstance();
        $money_instance = new MoneyListener($name);

        if($data === null){
            return;
        }
        
        if($data[1] === true){
            if($bank->checkAccount($name)){
                $player->sendMessage("§a[個人通知] §7口座が既に開設されております");
            }else{
                if($money_instance->getMoney() >= $bank->checkAccountOpeningFee()){
                    $bank->accountOpening($name);
                    $player->sendMessage("§a[個人通知] §7口座を開設しました");
                    $money_instance->reduceMoney($bank->checkAccountOpeningFee());
                    $bank->addBankMoney($bank->checkAccountOpeningFee());
                }else{
                    $player->sendMessage("§a[個人通知] §7口座開設手数料を支払う事ができませんでした");
                }
            }
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'口座開設',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"口座開設手数料:".Bank::getInstance()->checkAccountOpeningFee()."\n口座を開設する場合は口座開設ボタンにチェックを入れ、送信ボタンを押してください。既に口座を開設している場合は新規開設できません"
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