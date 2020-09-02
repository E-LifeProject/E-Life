<?php

namespace plugin\Form\Admin;

#Basic 
use pocketmine\Player;
use pocketmine\form\Form;


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
                ]
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