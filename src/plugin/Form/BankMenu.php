<?php

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Form\Bank\accountOpening;

class BankMenu implements Form{

    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }

        switch($data){
            //お引き出し
            case 0:
                $player->sendForm(new Purchase());
            break;

            //お預入れ
            case 1:
                $player->sendForm(new GovernmentDepositBalance());
            break;

            //残高照会
            case 2:
                $player->sendForm(new LocalFinance());
            break;

            //お振込
            case 3:
                $player->sendForm(new GovernmentOfficial());
            break;

            //口座開設
            case 4:
                $player->sendForm(new accountOpening());
            break;
        }
    }


    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'銀行ATM',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'text'=>'お引き出し'
                ],
                [
                    'text'=>'お預入れ'
                ],
                [
                    'text'=>'残高照会'
                ],
                [
                    'text'=>'お振込み'
                ],
                [
                    'text'=>'口座開設'
                ]
            ]
        ];
    }
}