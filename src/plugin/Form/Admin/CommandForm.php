<?php

namespace plugin\Form\Admin;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#Form
use plugin\Form\Admin\Command\BanCommand;
use plugin\Form\Admin\Command\PardonCommand;
use plugin\Form\Admin\Command\WhitelistCommand;
use plugin\Form\Admin\Command\MoneyCommand;

class CommandForm implements Form{

    //Formの処理
    public function handleResponse(Player $player, $data) : void{
    	if($data === null){
    		return;
    	}
    	switch($data){
    		// Ban
    		case 0:
    			$player->sendForm(new BanCommand());
    			break;

    		// Pardon
    		case 1:
    			$player->sendForm(new PardonCommand());
    			break;

    		// WhiteList
    		case 2:
    			$player->sendForm(new WhitelistCommand());
    			break;

            // Money
            case 3:
                $player->sendForm(new MoneyCommand());
                break;
    	}
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'管理者用メニュー(コマンド)',
            'content'=>'実行したい項目を選んでください',
            'buttons'=>[
                [
                    'text'=>'追放'
                ],
                [
                    'text'=>'追放解除'
                ],
                [
                    'text'=>'ホワイトリスト'
                ],
                [
                    'text'=>'お金'
                ]
            ]
        ];
    }

} 