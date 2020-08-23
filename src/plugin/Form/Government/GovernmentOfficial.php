<?php

namespace plugin\Form\Government;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

class GovernmentOfficial implements Form{
    public function handleResponse(Player $player, $data): void{
        if($data === null){
            return;
        }
    }

    public function jsonSerialize(){
        $text = <<<EOT
        【政府関係者】
        FonSoutan
        haya1007
        soradore
        ribnil
        charindou
        xtakumatutix
        Cookiettatchan
        EOT;
        
        return[
            'type'=>'custom_form',
            'title'=>'政府関係者一覧',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>$text
                ]
            ]
        ];
    }
}
