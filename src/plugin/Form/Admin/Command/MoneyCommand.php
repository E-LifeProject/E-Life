<?php

namespace plugin\Form\Admin\Command;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\form\Form;

class MoneyCommand implements Form{
    //Formの処理
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        $dropdown = $data[1];
        $target_name = $data[2];
        $amount = $data[3];

        $target = Server::getInstance()->getOfflinePlayer($target_name);
        switch ($dropdown) {
        	case 0: //seemoney
        		Server::getInstance()->dispatchCommand($player, 'seemoney '.$target->getName());
        		break;

        	case 1: //setmoney
				if($amount === ""){
					$player->sendMessage("§c金額が入力されていません。");
				}

				if(!is_numeric($amount)){
					$player->sendMessage("§c数字にして下さい。");
				}

        		Server::getInstance()->dispatchCommand($player, 'setmoney '.$target->getName().' '.$amount);
        		break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'MoneyCommand',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"MoneyCommand"
                ],
                [
                	'type'=>'dropdown',
                	'text'=>'実行する項目',
                	'options'=>[
                		'seemoney',
                		'setmoney'
                	]
                ],
                [
                    'type'=>'input',
                    'text'=>'対象の名前を入力してください。',
                    'placeholder'=>'PlayerName'
                ],
                [
                    'type'=>'input',
                    'text'=>"金額を入力してください\n※setmoneyの時のみ",
                    'placeholder'=>"amount"
                ]
            ]
        ];
    }
}