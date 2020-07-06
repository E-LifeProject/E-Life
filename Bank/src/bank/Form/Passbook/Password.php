<?php

namespace bank\Form\Passbook;

use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;
use bank\DataBase\CreateAccount;

class Password implements Form{
	public function __construct(Main $main, $index=0){
		$this->main = $main;
        $this->index = $index;
	}

	public function handleResponse(Player $player, $data):void{
		if ($data === null){
			return;
		}

		$pass = $data[1];
		$pass_check = $data[2];

		if($pass === $pass_check){
			$account = new CreateAccount($this->main);
			$account->enablePassbook($player, $pass, $this->index);
		} else {
			$player->sendMessage("§l§c>パスワードが合致しません<");
		}
	}

	public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'パスワードの変更',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"新規パスワードを入力してください。\nこのパスワードはお金を引出する際などに使用します。"
                ],
                [
                    'type'=>'input',
                    'text'=>'新規パスワード',
                    'placeholder'=>'aiueo'
                ],
                [
                    'type'=>'input',
                    'text'=>'再確認用(同じパスワードを入力してください)',
                    'placeholder'=>'aiueo'
                ]
            ]
        ];
	}
}