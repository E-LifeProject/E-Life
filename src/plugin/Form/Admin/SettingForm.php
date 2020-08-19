<?php

namespace plugin\Form\Admin;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\utils\Config;

#E-Life
use plugin\Form\Admin\LoanReview;
use plugin\Form\Admin\CommandForm;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class SettingForm implements Form{

	//Formの処理
	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}
		switch($data){
			// コマンド関連
			case 0:
				$player->sendForm(new CommandForm());
				break;

			// 銀行ローン審査
			case 1:
				if(count(ConfigBase::getFor(ConfigList::LOAN_REVIEW)->getAll()) == 0){
					$player->sendMessage("§a[個人通知] §7現在ローンのお申し込みはありません");
				}else{
					$player->sendForm(new LoanReview());
				}
			break;

			// NULL
			case 2:
				break;
		}

	}

	//表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'管理者用メニュー',
            'content'=>'実行したい項目を選んでください',
            'buttons'=>[
                [
                    'text'=>'コマンド関連'
                ],
                [
                    'text'=>'銀行ローン審査'
                ],
                [
                    'text'=>'NULL'
                ]

            ]
        ];
    }

}