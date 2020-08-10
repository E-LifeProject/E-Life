<?php

namespace plugin\Form\Shop;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Form\Shop\ConfirmationForm;


class CountForm implements Form{

    public function __construct($shopData){
        $this->shopData = $shopData;
        $this->club = ConfigBase::getFor(ConfigList::CLUB);
    }

    public function handleResponse(Player $player , $data):void {
        $name = $player->getName();
        if($data === null){
            return;
        }

        if($this->club->exists($name)){
            $fee =0;
        }else{
            $fee = 100;
        }

        //買取金額の二倍の額で販売する。その差分を政府の利益とし運営していく（この利率は運営してみて変更する可能性はある）
        $total = $this->shopData['price']*2*$data[1]+$fee;
        $player->sendForm(new ConfirmationForm($this->shopData,$data[1],$total,$fee));
    }

    public function jsonSerialize(){
        $itemName = $this->shopData['jpnName'];

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