<?php

namespace plugin\Form\Admin;

#Basic 
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\Utils\Config;
use pocketmine\form\Form;

#E-Life
use plugin\Utils\Punishment;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class PunishmentForm implements Form{
    public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}
		switch($data){
			// 違反管理
			case 0:
				$player->sendForm(new addPunishment());
			break;

			// 違反取下げ
			case 1:
				$player->sendForm(new withdrawalPunishment());
			break;
		}

	}

	//表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'違反管理メニュー',
            'content'=>'実行したい項目を選んでください',
            'buttons'=>[
                [
                    'text'=>'違反追加'
                ],
                [
                    'text'=>'違反取下げ'
                ],
            ]
        ];
    }
}

//違反追加
class addPunishment implements Form{
    public function handleResponse(Player $player, $data) : void{
        $name = $player->getName();

		if($data === null){
			return;
        }
        
        $config = ConfigBase::getFor(ConfigList::PUNISHMENT);

        switch($data[1]){
            case 0:
                $reason = "暴言・誹謗中傷";
            break;
        }

        switch($data[2]){
            //警告
            case 0:
                Punishment::getInstance()->addPunishment($data[0],$reason,$name);
                $player->sendMessage("§a[個人通知] §7警告を付与しました");
            break;
            //入室禁止
            case 1:
                Punishment::getInstance()->addPunishment($data[0],$reason,$name);
                if(!$config->exists($data[0])){
                    Punishment::getInstance()->addPunishment($data[0],$reason,$name);
                }
                $player->sendMessage("§a[個人通知] §7入室禁止にしました");
            break;
        }
        
	}

	//表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'違反追加',
            'content'=>[
                [
                    'type'=>'input',
                    'text'=>'違反プレーヤー',
                    'placeholder'=>'違反者名'
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'違反項目',
                    'options'=>[
                        '暴言・誹謗中傷',
                    ],
                    'default'=> 0
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'処罰選択',
                    'options'=>[
                        '警告付与',
                        '入室禁止'
                    ]
                ]
            ]
        ];
    }
}

//違反取下げ
class withdrawalPunishment implements Form{

    public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
        }
        
        $player->sendForm(new confirmationPunishment($data));
	}

	//表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'違反追加',
            'content'=>[
                [
                    'type'=>'input',
                    'text'=>'違反取下げプレーヤー',
                    'placeholder'=>'違反取下げ者名'
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'取下げ理由',
                    'options'=>[
                        '誤Ban',
                    ],
                    'default'=> 0
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'実行する項目選択',
                    'options'=>[
                        '警告解除',
                        '入室禁止解除'
                    ],
                ]
            ]
        ];
    }
}



class confirmationPunishment implements Form{

    public function __construct($data){
        $this->data = $data;
    }

    public function handleResponse(Player $player, $data) : void{
        $name = $player->getName();
		if($data === null){
			return;
        }
        
        switch($this->data[2]){
            case 0:
                if(!Server::getInstance()->getNameBans()->isBanned($target)){
                    Punishment::getInstance()->cancelPunishment($this->data[0],$this->key[$data[0]],$name);
                    $player->sendMessage("§a[個人通知] §7警告解除しました");
                }else{
                    $player->sendMessage("§a[個人通知] §7入室禁止になっていないプレーヤーです");
                }
            break;
            case 1:
                if(Server::getInstance()->getNameBans()->isBanned($target)){
                    Server::getInstance()->getNameBans()->remove($target);
                    Punishment::getInstance()->cancelPunishment($this->data[0],$this->key[$data[0]],$name);
                    $player->sendMessage("§a[個人通知] §7入室禁止を解除しました");
                }else{
                    $player->sendMessage("§a[個人通知] §7入室禁止になっていないプレーヤーです");
                }
            break;
        }
	}

	//表示するForm
    public function jsonSerialize(){
        $config = ConfigBase::getFor(ConfigList::PUNISHMENT);
        $this->configData = $config->getNested($this->data[0].".Reason");

        $i = 1;
        foreach($this->configData as $key => $reason){
            $buttons[] = $i.$reason;
            $this->key[] = $key;
            $i++;
        }

        return[
            'type'=>'custom_form',
            'title'=>'取下げ違反項目',
            'content'=>[
                [
                    'type'=>'dropdown',
                    'text'=>'取下げ項目',
                    'options'=>$buttons,
                    'default'=> 0
                ]
            ]
        ];
    }
}