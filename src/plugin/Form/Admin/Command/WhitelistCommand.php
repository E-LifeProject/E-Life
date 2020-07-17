<?php

namespace plugin\Form\Admin\Command;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\form\Form;

class WhitelistCommand implements Form{
    //Formの処理
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        $dropdown = $data[1];
        $target_name = $data[2];

        switch ($dropdown) {
            case 0: //on
                Server::getInstance()->setConfigBool("white-list", true);
                $player->sendMessage("§a[個人通知] §7ホワイトリストを有効にしました");
                break;

            case 1://off
                Server::getInstance()->setConfigBool("white-list", false);
                $player->sendMessage("§a[個人通知] §7ホワイトリストを無効にしました");
                break;

        	case 2: //add
        		Server::getInstance()->getOfflinePlayer($target_name)->setWhitelisted(true);
        		$player->sendMessage("§a[個人通知] §7".$target->getName()."をホワイトリストに追加しました");
        		break;
        	
        	case 3: //remove
        		Server::getInstance()->getOfflinePlayer($target_name)->setWhitelisted(false);
        		$player->sendMessage("§a[個人通知] §7".$target->getName()."をホワイトリストから除外しました");
        		break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'ホワイトリスト管理フォーム',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"Dropdownが「有効」,「無効」の時は、名前の記入は入りません"
                ],
                [
                	'type'=>'dropdown',
                	'text'=>'実行する項目',
                	'options'=>[
                        '有効',
                        '無効',
                		'プレーヤー追加',
                		'プレーヤー除外'
                	]
                ],
                [
                    'type'=>'input',
                    'text'=>'対象の名前を入力してください。',
                    'placeholder'=>'プレーヤー名'
                ]
            ]
        ];
    }
}