<?php

namespace plugin\Form\Shop;

#Basic
use pocketmine\form\Form;
use pocketmine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


class ShopForm implements Form{

    //Formの処理
    public function handleResponse(Player $player,$data):void{
	    
	    if($data === null) {
		    return;
	    }

	    /**
         * $shop = ConfigBase::getFor(ConfigList::SHOP);
	     * $club = ConfigBase::getFor(ConfigList::CLUB);
         */
        
        switch($data){
            case 0:
                $player->sendForm(new ToolForm());
            break;
            case 1:
                $player->sendForm(new BookForm());
            break;
            case 2:
                $player->sendForm(new OtherForm());
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
                'text'=>'道具'
            ],
            [
                'text'=>'ブロック'
            ],
            [
                'text'=>'その他'
            ]
        ];
    }
}