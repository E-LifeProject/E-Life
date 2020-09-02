<?php

namespace plugin\Form\Admin;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\utils\Config;

#E-Life
use plugin\Form\Admin\LoanReview;
use plugin\Form\Admin\CommandForm;
use plugin\Form\Admin\PunishmentForm;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class SettingForm implements Form{

	//Formの処理
	public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
		}
		switch($data){
			// 違反管理
			case 0:
				$player->sendForm(new PunishmentForm());
			break;

			// 銀行ローン審査
			case 1:
				if(count(ConfigBase::getFor(ConfigList::LOAN_REVIEW)->getAll()) == 0){
					$player->sendMessage("§a[個人通知] §7現在ローンのお申し込みはありません");
				}else{
					$player->sendForm(new LoanReview());
				}
			break;

			// コマンド関連
			case 2:
				$player->sendForm(new CommandForm());
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
                    'text'=>'違反管理'
                ],
                [
                    'text'=>'銀行ローン審査'
                ],
                [
                    'text'=>'コマンド関連'
                ]

            ]
        ];
    }
}