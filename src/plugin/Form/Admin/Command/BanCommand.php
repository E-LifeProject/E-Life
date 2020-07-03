<?php

namespace plugin\Form\Admin\Command;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\form\Form;

class BanCommand implements Form{

    //Formの処理
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        $target_name = $data[1];
        $target = Server::getInstance()->getOfflinePlayer($target_name);
        if(!is_null($target) || $target_name === ""){
	        if(!$target->isOp()){
	        	$target->setBanned(true);
	        	$player->sendMessage("§a".$target->getName()."をBanしました。");
	        }else{
	        	$player->sendMessage("§c".$target->getName()."は権限者なのでBanできません。");
	        }
	    } else {
	    	$player->sendMessage("§c存在しないプレイヤーです。");
	    }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'BanCommand',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"BanCommand"
                ],
                [
                    'type'=>'input',
                    'text'=>'対象の名前を入力してください。',
                    'placeholder'=>'PlayerName'
                ]
            ]
        ];
    }
}