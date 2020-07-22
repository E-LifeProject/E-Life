<?php

namespace plugin\Form;

#Basic
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\Player;
use pocketmine\form\Form;

class TermsForm implements Form{

    //Formの処理  
    public function handleResponse(Player $player, $data):void{
        //同意した場合はconfigに名前を記録する
        //同意しない場合はkick
        if($data === true) {
        	$player_conig = ConfigBase::getFor(ConfigList::PLAYER);
            $player->sendMessage("§a[個人通知] §7利用規約に同意しました");
            $player_conig->set($player->getName());
            $player_conig->save();
        }else{
            $player->kick("利用規約に同意した方のみ参加できます",false);
        }
    }

    //表示するフォーム
    public function jsonSerialize(){
        return[
            'type'=>'modal',
            'title'=>'利用規約',
            'content'=>'利用規約(仮)',
            'button1'=>'同意します',
            'button2'=>'同意しません'
        ];
    }
}
