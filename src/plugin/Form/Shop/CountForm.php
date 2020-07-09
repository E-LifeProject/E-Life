<?php

namespace plugin\Form\Shop;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;



class CountForm implements Form{

    public function __construct($shopData){
        $this->shopData = $shopData;
        $this->club = ConfigBase::getFor(ConfigList::CLUB);
    }

    public function handleResponse(Player $player , $data):void {
        if($data === null){
            return;
        }

        if($this->club->exists($name)){
            $fee =0;
        }else{
            $fee = 100;
        }
        $total = $this->shopData['price']*$data[1]+$fee;
        $player->sendForm(new ConfirmationForm($shopData,$data[1],$total,$fee));
    }

    public function jsonSerialize(){
        $itemName = $this->shopData['name'];

        return[
            'type'=>'custom_form',
            'title'=>'公式ショップ',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>'購入アイテム:'.$itemName."\n購入したい個数を選択してください"
                ],
                [
                    'type'=>'slider',
                    'text'=>'購入個数',
                    'min'=>1,
                    'max'=>64,
                    'default'=>32
                ]
            ]
        ];
    }
}
?>