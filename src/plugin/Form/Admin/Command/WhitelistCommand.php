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
                $player->sendMessage("§aホワイトリストを有効にしました。");
                break;

            case 1://off
                Server::getInstance()->setConfigBool("white-list", false);
                $player->sendMessage("§aホワイトリストを無効にしました。");
                break;

        	case 2: //add
        		Server::getInstance()->getOfflinePlayer($target_name)->setWhitelisted(true);
        		$player->sendMessage("§a".$target->getName()."をホワイトリストに追加しました。");
        		break;
        	
        	case 3: //remove
        		Server::getInstance()->getOfflinePlayer($target_name)->setWhitelisted(false);
        		$player->sendMessage("§a".$target->getName()."をホワイトリストから除外しました。");
        		break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'WhitelistCommand',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"WhitelistCommand\nDropdownが「on」,「off」の時は、名前の記入は入りません"
                ],
                [
                	'type'=>'dropdown',
                	'text'=>'実行する項目',
                	'options'=>[
                        'on',
                        'off',
                		'add',
                		'remove'
                	]
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