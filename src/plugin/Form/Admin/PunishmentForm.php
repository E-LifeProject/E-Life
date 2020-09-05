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
                    'text'=>'警告付与・入室禁止'
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
		if($data === null){
			return;
        }
        $name = $player->getName();
        $config = ConfigBase::getFor(ConfigList::PUNISHMENT);
        $punishment = Punishment::getInstance();

        switch($data[1]){
            case 0:
                $reason = "暴言・誹謗中傷";
            break;
        }

        switch($data[2]){
            //警告
            case 0:
                $punishment->addPunishment($data[0],$reason,$name);
                $player->sendMessage("§a[個人通知] §7".$data[0]."に警告を付与しました");
            break;

            //入室禁止
            case 1:
                Server::getInstance()->getNameBans()->addBan($data[0],$reason,null,$name);
                $player->sendMessage("§a[個人通知] §7".$data[0]."を入室禁止にしました");
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
        $instance = Server::getInstance()->getNameBans();
        $punishment = Punishment::getInstance();

        if($instance->isBanned($data[0])){//もしBanされていたら
            $instance->remove($data[0]);
            $player->sendMessage("§a[個人通知] §7".$data[0]."の入室禁止を解除しました");
        }else{
            if($punishment->checkPunishment($data[0])){
                $punishment->cancelPunishment($data[0]);
                $player->sendMessage("§a[個人通知] §7".$data[0]."の警告を解除しました");
            }else{
                $player->sendMessage("§a[個人通知] §7".$data[0]."は処罰が課せられておりません");
            }
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
                ]
            ]
        ];
    }
}