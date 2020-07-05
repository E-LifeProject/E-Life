<?php

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

class TermsForm implements Form{

    public function __construct($main){
        $this->main = $main;
    }

    //Formの処理  
    public function handleResponse(Player $player, $data):void{
        //同意した場合はconfigに名前を記録する
        //同意しない場合はkick
        if($data === true){ 
            $player->sendPopUp("§a通知>>利用規約に同意しました\n\n");
            $this->main->player->set($player->getName());
            $this->main->player->save();
        }else{
            $player->kick("利用規約に同意した方のみ参加できます",false);
        }
    }

    //表示するフォーム
    public function jsonSerialize(){
        return[
            'type'=>'modal',
            'title'=>'利用規約',
            'content'=>'利用規約(仮)',
            'button1'=>'同意します',
            'button2'=>'同意しません'
        ];
    }
}
?>