<?php

namespace plugin\form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Server;


class WorldForm implements Form{
    
    //Formの処理
    public function handleResponse(Player $player, $data):void {
        if($data === null){
            return;
        }

        switch($data){
            //ロビーのリスポーン地点にテレポート
            case 0:
                $lobby = new Position(230,8,228,Server::getInstance()->getLevelByName("lobby"));
                $player->teleport($lobby);
            break;

            case 1:
                $nature = new Position(255,76,256,Server::getInstance()->getLevelByName("nature"));
                $player->teleport($nature);
            break;

            case 2:
                $life = new Position(378,4,135,Server::getInstance()->getLevelByName("life"));
                $player->teleport($life);
            break;

        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'ワールド選択',
            'content'=>'行きたいワールドを選択してください',
            'buttons'=>[
                [
                    'text'=>'ロビー',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/gui/newgui/Realms',
                    ]
                ],
                [
                    'text'=>'資源ワールド',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/icon_recipe_nature',
                    ]
                ],
                [
                    'text'=>'生活/--市',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/icon_recipe_item',
                    ]
                ]
            ]
        ];
    }
}
?>