<?php

namespace plugin\Form\Admin;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\utils\Config;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Utils\Reliability;


class ReliabilityForm implements Form{
    //Formの処理
	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
        }
        $config = ConfigBase::getFor(ConfigList::RELIABILITY);

        if($config->exists($data[0])){
            $instance = new Reliability($data[0]);
            $instance->setManual($data[1]);
            $player->sendMessage("§a[個人通知] §7信用度を設定しました");
        }else{
            $player->sendMessage("§a[個人通知] §7そのプレーヤーは存在しません");
        }
	}

	//表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'信用度管理',
            'content'=>[
                [
                    'type'=>'input',
                    'text'=>'設定プレーヤー名',
                ],
                [
                    'type'=>'slider',
                    'text'=>'信用度設定値',
                    'min'=>0,
                    'max'=>30,
                    'default'=>10
                ]
            ]
        ];
    }
}