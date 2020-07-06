<?php

namespace plugin\Form\Club;

#Basic
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\Player;
use pocketmine\form\Form;

#EconomyAPI
use onebone\economyapi\EconomyAPI;


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
	    $money = EconomyAPI::getInstance()->mymoney($player);

	    $club = ConfigBase::getFor(ConfigList::CLUB);

        switch($data[1]){
            //10日プランの場合の処理
            case 0:
                if(!$club->exists($name)){
                    if($money>=1000){
                        EconomyAPI::getInstance()->reduceMoney($player,1000);
                        $club->set($name,date("Y/m/d",strtotime("10 day")));
                        $club->save();
                        $player->sendPopUp("§a通知>>20日プランでE-Clubに加入しました\n\n");
                    }else{
                        $player->sendPopUp("§a通知>>所持金が足りません\n\n");
                    }
                }else{
                    $player->sendPopUp("§a通知>>すでに加入されています 期限:".$this->main->club->get($name)."\n\n");
                }
            break;

            //20日プランの場合の処理
            case 1:
                if(!$club->exists($name)){
                    if($money>=2000){
                        EconomyAPI::getInstance()->reduceMoney($player,2000);
                        $club->set($name,date("Y/m/d",strtotime("20 day")));
                        $club->save();
                        $player->sendPopUp("§a通知>>20日プランでE-Clubに加入しました\n\n");
                    }else{
                        $player->sendPopUp("§a通知>>所持金が足りません\n\n");
                    }
                }else{
                    $player->sendPopUp("§a通知>>すでに加入されています 期限:".$club->get($name)."\n\n");
                }
            break;

            //30日プランの場合の処理
            case 2:
                if(!$club->exists($name)){
                    if($money>=3000){
                        EconomyAPI::getInstance()->reduceMoney($player,3000);
                        $club->set($name,date("Y/m/d",strtotime("30 day")));
                        $club->save();
                        $player->sendPopUp("§a通知>>30日プランでE-Clubに加入しました\n\n");
                    }else{
                        $player->sendPopUp("§a通知>>所持金が足りません\n\n");
                    }
                }else{
                    $player->sendPopUp("§a通知>>すでに加入されています 期限:".$club->get($name)."\n\n");
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