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
use plugin\Form\Job\JobForm;


class MainMenu implements Form{

    public function __construct($main){
        $this->main = $main;
    }

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
            
            //仕事に関するForm
            case 1:
                $player->sendForm(new JobForm($this->main));
            break;
            //チャットに関するForm
            case 2:
                $player->sendForm(new ChatForm());
            break;

            //土地に関するForm
            case 3:
                $player->sendForm(new LandForm());
            break;

            //ショップに関するForm
            case 4:
                $player->sendForm(new ShopForm($this->main));
            break;

            //E-Clubに関するForm
            case 5:
                $player->sendForm(new ClubForm($this->main));
            break;

            //管理用Form
            //OPのみFormを開けるように
            case 6:
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