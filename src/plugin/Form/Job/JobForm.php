<?php

namespace plugin\Form\Job;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\Server;

#Form
use plugin\Form\Job\JoinJob;


/**
 * Jobの退職メッセージは
 * EconomyJobを改変させる
 */

class JobForm implements Form{

    //Formの処理
    public function handleResponse(Player $player, $data):void{
        $name = $player->getName();

        if($data === null){
            return;
        }
        switch($data){
            case 0:
                $player->sendForm(new JoinJob($name));
            break;

            case 1:
                Server::getInstance()->dispatchCommand($player, 'job retire');
            break;

            case 2:
                $player->sendForm(new AboutJob());//まだ作ってない
            break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'職業メニュー',
            'content'=>"実行したい項目を選択してください",
            'buttons'=>[
                [
                    'text'=>'職業に就く'
                ],
                [
                    'text'=>'職業をやめる'
                ],
                [
                    'text'=>'職業について'
                ]
            ]
        ];
    }
}
?>