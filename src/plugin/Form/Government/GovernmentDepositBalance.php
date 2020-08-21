<?php

namespace plugin\Form\Government;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Government\GovernmentMoney;
use plugin\Economy\Government\Storehouse;


class GovernmentDepositBalance implements Form{

    public function handleResponse(Player $player, $data):void{
        if($data === null){
            return;
        }
    }

    public function jsonSerialize(){
        $government = GovernmentMoney::getInstance();
        $item = Storehouse::getInstance();
        $text = <<<"EOT"
        【保有資源】
        石ブロック:{$item->getItemCount("stone")}個
        鉄鉱石:{$item->getItemCount("ironOre")}個
        オークの原木:{$item->getItemCount("oka")}個
        マツの原木:{$item->getItemCount("spruce")}個
        シラカバの原木:{$item->getItemCount("birch")}個
        ジャングルの原木:{$item->getItemCount("jungle")}個
        アカシアの原木:{$item->getItemCount("acacia")}個
        ダークオークの原木:{$item->getItemCount("dark_oka")}個
        EOT;

        return[
            'type'=>'custom_form',
            'title'=>'政府財政状況照会',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"【政府財政状況】\n国庫金:".$government->getMoney()."円"
                ],
                [
                    'type'=>'label',
                    'text'=>$text
                ]
            ]
        ];
    }
}