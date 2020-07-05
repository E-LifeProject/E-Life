<?php

namespace plugin\Form\Job;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\Server;

use onebone\economyjob\EconomyJob;



/**
 * Jobの変更メッセージは
 * EconomyJobを改変させる
 */

class JoinJob implements Form{

    public function __construct($main,$name){
        $this->main = $main;
        $this->name = $name;
    }

    //Formの処理
    public function handleResponse(Player $player, $data):void{
        $name = $player->getName();

        if($data === null){
            return;
        }
        if($this->main->jobCountArray[$this->name] > 0){
            switch($data){
                case 0:
                    Server::getInstance()->dispatchCommand($player, 'job join tree-cutter');
                break;
    
                case 1:
                    Server::getInstance()->dispatchCommand($player, 'job join miner');
                break;
    
                case 2:
                    Server::getInstance()->dispatchCommand($player, 'job join tree-planter');
                break;
            }
            $this->main->jobCountArray[$this->name] -= 1;
        }else{
            $player->sendMessage("変更上限回数に達している為変更出来ません");
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'職業選択',
            'content'=>"変更したい職業を選択してください \n本日の変更可能回数:".$this->main->jobCountArray[$this->name]."回",
            'buttons'=>[
                [
                    'text'=>'伐採職人',
                    'image'=>[
                        'type'=>'path',
                        'Data'=>'textures/items/stone_axe'
                    ]
                ],
                [
                    'text'=>'鉱夫',
                    'image'=>[
                        'type'=>'path',
                        'Data'=>'textures/items/stone_pickaxe'
                    ]
                ],
                [
                    'text'=>'植林職人',
                    'image'=>[
                        'type'=>'path',
                        'Data'=>'textures/blocks/sapling_oak'
                    ]
                ]
            ]
        ];
    }
}
?>