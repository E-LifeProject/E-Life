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

        $dropdown = $data[0];
        $target_name = $data[1];
        $amount = $data[2];

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
            'title'=>'お金管理フォーム',
            'content'=>[
                [
                	'type'=>'dropdown',
                	'text'=>'実行する項目',
                	'options'=>[
                		'他プレーヤーの所持金の確認',
                		'他プレーヤーの所持金を変更'
                	]
                ],
                [
                    'type'=>'input',
                    'text'=>'対象の名前を入力してください。',
                    'placeholder'=>'プレーヤー名'
                ],
                [
                    'type'=>'input',
                    'text'=>"金額を入力してください\n※setmoneyの時のみ",
                    'placeholder'=>"金額"
                ]
            ]
        ];
    }
}