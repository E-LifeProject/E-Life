<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;

class accountOpening implements Form{
    public function handleResponse(Player $player, $data): void{
        if($data === null){
            return;
        }
        
        if($data[1] === true){
            Bank::getInstance()->accountOpening($player);
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'口座開設',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"口座開設手数料:1500円\n口座を開設する場合は口座開設ボタンにチェックを入れ、送信ボタンを押してください。既に口座を開設している場合は新規開設できません"
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