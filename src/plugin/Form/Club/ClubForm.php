<?php

namespace plugin\Form\Club;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#Form
use plugin\Form\Club\JoinClub;

class ClubForm implements Form{

    public function __construct($main){
        $this->main = $main;
    }

    //Formの処理
    public function handleResponse(Player $player,$data):void{
        $name = $player->getName();
        if($data === null){
            return;
        }
        switch($data){
            //E-Club加入に関するForm
            case 0:
                $player->sendForm(new JoinClub($this->main));
            break;

            //E-Club脱退に関するForm
            case 1:
                if($this->main->club->exists($name)){
                    $player->sendForm(new WithdrawalClub($this->main));
                }else{
                    $player->sendPopUp("§a通知>>E-Clubに加入していません\n\n");
                }
            break;

            //E-Clubの説明
            case 2:
                $player->sendForm(new Overview());
            break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'E-Clubメニュー',
            'content'=>'実行したいものを選んで下さい',
            'buttons'=>[
                [
                    'text'=>'E-Club加入',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/permissions_op_crown'
                    ]
                ],
                [
                    'text'=>'E-Club脱退',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/deop'
                    ]
                ],
                [
                    'text'=>'E-Clubとは？',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/items/book_portfolio'
                    ]
                ]
            ]
        ];
    }
}
?>