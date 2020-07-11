<?php

namespace plugin\Form\Chat;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#EoconomyAPI
use onebone\economyapi\EconomyAPI;

#Form
use plugin\Form\Chat\SendChat;



class ChatForm implements Form{

    /**
     * 送信のフォームはもし所持金が100円以下であれば
     * 送信Formが表示されないように
     */
    //Formの処理
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        switch($data){
            //送信に関するForm
            case 0:
                $money = EconomyAPI::getInstance()->myMoney($player);
                if($money<=100){
                    $player->sendPopUp("§a通知>>送信料100円が払えません\n\n");
                }else{
                    $player->sendForm(new SendChat($money));
                }
            break;
            
            //受信に関するForm
            case 1:
               $player->sendForm(new ReceiveChat());
            break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'チャットメニュー',
            'content'=>'実行したい項目を選んでください',
            'buttons'=>[
                [
                    'text'=>'チャットを送信'
                ],
                [
                    'text'=>'チャットの受信'
                ],
                [
                    'text'=>'権限者へ連絡'
                ]

            ]
        ];
    }
}
?>