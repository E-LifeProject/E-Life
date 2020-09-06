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
			//警告付与
			case 0:
				$player->sendForm(new addPunishment());
            break;
            
            //入室禁止
            case 1:
                $player->sendForm(new addBan());
            break;
		}
	}

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'違反管理メニュー',
            'content'=>'実行したい項目を選んでください',
            'buttons'=>[
                [
                    'text'=>'警告付与'
                ],
                [
                    'text'=>'入室禁止'
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
            case 0:
                $count = 1;
            break;
            case 1:
                $count = 2;
            break;
        }

        if(Server::getInstance()->getNameBans()->isBanned($data[0])){//入室禁止であったら
            $player->sendMessage("§a[個人通知] §7".$data[0]."は入室禁止措置が取られています");
        }else{
            $punishment->addPunishment($data[0],$count,$reason,$name);
            $player->sendMessage("§a[個人通知] §7".$data[0]."に警告を付与しました");
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
                    'text'=>'違反点数',
                    'options'=>[
                        '1',
                        '2'
                    ]
                ]
            ]
        ];
    }
}

//入室禁止者追加
class addBan implements Form{

    public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
        }
        $instance = Server::getInstance()->getNameBans();
        $punishment = Punishment::getInstance();

        switch($data[1]){
            case 0:
                $reason = "暴言・誹謗中傷";
            break;
        }

        if($instance->isBanned($data[0])){//既に入室禁止であったら
            $player->sendMessage("§a[個人通知] §7".$data[0]."は既に入室禁止措置が取られています");
        }else{
            if($punishment->checkPunishment($data[0])){//もし警告付与者なら警告データを削除
                $config = ConfigBase::getFor(ConfigList::PUNISHMENT);
                $config->remove($data[0]);
                $config->save();
            }
            $instance->addBan($data[0],$reason,null,$player->getName());
            $player->sendMessage("§a[個人通知] §7".$data[0]."に入室禁止措置を取りました");
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
                    'text'=>'入室禁止プレーヤー',
                    'placeholder'=>'違反者名'
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'入室禁止理由',
                    'options'=>[
                        '暴言・誹謗中傷',
                    ],
                    'default'=> 0
                ]
            ]
        ];
    }
}