<?php

namespace plugin\Form\Shop;

#Basic
use pocketmine\form\Form;
use pocketmine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Form\Shop\ToolForm;


class ShopForm implements Form{

    //Formの処理
    public function handleResponse(Player $player,$data): void{
	    
	    if($data === null) {
		    return;
	    }


        switch($data){
         /**case 0:
                $player->sendForm(new ToolForm());
            break; */
            case 0:
                $player->sendForm(new BlockForm());
            break;
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'公式ショップ',
            'content'=>'購入したいものを選択してください',
            'buttons'=>[
                [
                    'text'=>'ブロック'
                ]
            ]
        ];
    }
}
?>