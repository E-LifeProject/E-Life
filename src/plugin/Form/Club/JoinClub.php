<?php

namespace plugin\Form\Club;

#Basic
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\MoneyListener;

class JoinClub implements Form{


    /**
     * E-Clubは重複登録が出来ないので期日が過ぎたら
     * 再度登録してもらうようにして、鯖への参加率を高める
     */
    //Formの処理
    public function handleResponse(Player $player,$data):void{
	    if($data === null){
		    return;
	    }

	    $name = $player->getName();
        $money_instance = new MoneyListener($name);
	    $club = ConfigBase::getFor(ConfigList::CLUB);

        switch($data[1]){
            //10日プランの場合の処理
            case 0:
                if(!$club->exists($name)){
                    if($money>=1000){
                        $money_instance->reduceMoney(1000);
                        $club->set($name,date("Y/m/d",strtotime("10 day")));
                        $club->save();
                        $player->sendMessage("§a[個人通知] §710日プランでE-Clubに加入しました");
                    }else{
                        $player->sendMessage("§a[個人通知] §7所持金が足りません");
                    }
                }else{
                    $player->sendMessage("§a[個人通知] §7すでに加入されています 期限:".$this->main->club->get($name));
                }
            break;

            //20日プランの場合の処理
            case 1:
                if(!$club->exists($name)){
                    if($money>=2000){
                        $money_instance->reduceMoney(2000);
                        $club->set($name,date("Y/m/d",strtotime("20 day")));
                        $club->save();
                        $player->sendMessage("§a[個人通知] §720日プランでE-Clubに加入しました");
                    }else{
                        $player->sendMessage("§a[個人通知] §7所持金が足りません");
                    }
                }else{
                    $player->sendMessage("§a[個人通知] §7すでに加入されています 期限:".$this->main->club->get($name));
                }
            break;

            //30日プランの場合の処理
            case 2:
                if(!$club->exists($name)){
                    if($money>=3000){
                        $money_instance->reduceMoney($player,3000);
                        $club->set($name,date("Y/m/d",strtotime("30 day")));
                        $club->save();
                        $player->sendMessage("§a[個人通知] §730日プランでE-Clubに加入しました");
                    }else{
                        $player->sendMessage("§a[個人通知] §7所持金が足りません");
                    }
                }else{
                    $player->sendMessage("§a[個人通知] §7すでに加入されています 期限:".$this->main->club->get($name));
                }
            break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'E-Club加入',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"E-Clubの加入期間を選択してください。\nまた加入後の返金は出来ませんのでご了承ください。"
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'加入期間',
                    'options'=>[
                        '10日',
                        '20日',
                        '30日'
                    ],
                    'default'=>0
                ]
            ]
        ];
    }
}