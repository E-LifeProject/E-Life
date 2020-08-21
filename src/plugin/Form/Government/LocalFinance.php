<?php
namespace plugin\Form\Government;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

class LocalFinance implements Form{
    public function  handleResponse(Player $player, $data):void{
        if($data === null){
            return;
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'地方財政状況照会',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"【---市】\n---円"
                ]
            ]
        ];
    }
}