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
                        'Data'=>'textures/gui/newgui/Realms',
                    ]
                ],
                [
                    'text'=>'資源ワールド',
                    'image'=>[
                        'type'=>'path',
                        'Data'=>'textures/ui/icon_recipe_nature',
                    ]
                ]
            ]
        ];
    }
}
?>