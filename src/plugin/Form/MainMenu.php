<?php

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#Form
use plugin\Form\WorldForm;
use plugin\Form\Admin\SettingForm;
use plugin\Form\Chat\ChatForm;
use plugin\Form\Shop\ShopForm;
use plugin\Form\Club\ClubForm;


class MainMenu implements Form{

    //Formの処理
    public function handleResponse(Player $player, $data):void{
        if($data === null){
            return;
        }
        switch($data){
            //ワールド移動に関するForm
            case 0:
                $player->sendForm(new WorldForm());
            break;

            //チャットに関するForm
            case 1:
                $player->sendForm(new ChatForm());
            break;

            //土地に関するForm
            case 2:
                $player->sendForm(new LandForm());
            break;

            //ショップに関するForm
            case 3:
                $player->sendForm(new ShopForm());
            break;

            //E-Clubに関するForm
            case 4:
                $player->sendForm(new ClubForm());
            break;

            //管理用Form
            //OPのみFormを開けるように
            case 5:
                if($player->isOp()){
                    $player->sendForm(new SettingForm());
                }else{
                    $name = $player->getName();
                    $player->sendPopUp("§a通知>>".$name."さんは開くことが出来ません\n\n");
                }
            break;
        }

    }



    //表示するフォーム
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'メインメニュー',
            'content'=>'選択してください',
            'buttons'=>[
                [
                    'text'=>'ワールド移動',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/World'
                    ]
                ],   
                [
                    'text'=>'仕事変更',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/gui/newgui/anvil-hammer'
                    ]
                ],
                [
                    'text'=>'チャット',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/Feedback'
                    ]
                ],
                [
                    'text'=>'不動産登記',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/icon_book_writable'
                    ]
                ],
                [
                    'text'=>'公式ショップ',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/trade_icon'
                    ]
                ],
                [
                    'text'=>'E-Club加入',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/icon_best3'
                    ]
                ],
                [
                    'text'=>'管理フォーム',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/settings_glyph_color_2x'
                    ]
                ]
            ]
        ];
    }
}
?>