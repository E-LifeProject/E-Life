<?php

namespace plugin\Form\Admin\Command;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\form\Form;

class PardonCommand implements Form{

    //Formの処理
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        $target_name = $data[1];
        $target = Server::getInstance()->getOfflinePlayer($target_name);
        if(!is_null($target) || $target_name === ""){
            if($target->isBanned()){
                $target->setBanned(false);
            }else{
                $player->sendMessage("§a".$target->getName()."のBanを解除しました。");
            }
	    } else {
	    	$player->sendMessage("§c存在しないプレイヤーです。");
	    }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'追放解除フォーム',
            'content'=>[
                [
                    'type'=>'input',
                    'text'=>'対象の名前を入力してください。',
                    'placeholder'=>'プレーヤー名'
                ]
            ]
        ];
    }
}