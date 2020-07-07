<?php

namespace plugins\Form\Shop\Tool;

#Basic
use pocketmine\form\Form;
use pocketmine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


class ToolForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                $player->sendForm(new PickaxeForm());
            break;
            case 1:
                $player->sendForm(new AxeForm());
            break;
            case 2:
                $player->sendForm(new ShovelForm());
            break;
            case 3:
                $player->sendForm(new HoeForm());
            break;
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'公式ショップ',
            'content'=>'購入したいものを選択してください',
            'buttons'=>[
                [
                    'text'=>'ツルハシ'
                ],
                [
                    'text'=>'斧'
                ],
                [
                    'text'=>'シャベル'
                ],
                [
                    'text'=>'クワ'
                ]
            ]
        ];
    }
}
?>