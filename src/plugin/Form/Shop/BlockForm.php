<?php

namespace plugin\Form\Shop;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Form\Shop\CountForm;


class BlockForm implements Form{

    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                $player->sendForm(new StoneForm());
            break;
            case 1:
                $player->sendForm(new WoodForm());
            break;
            case 2:
                $player->sendForm(new BuildingForm());
            break;
            case 3:
                $player->sendForm(new OtherBlockForm());
            break;
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'公式ショップ',
            'content'=>'購入したいものを選択してください',
            'buttons'=>[
                [
                    'text'=>'石ブロック'
                ],
                [
                    'text'=>'木材ブロック'
                ]
            ]
        ];
    }
}

class StoneForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        $name = $player->getName();
        $shop = ConfigBase::getFor(ConfigList::PURCHASE);
        $club = ConfigBase::getFor(ConfigList::CLUB);

        if($data === null){
            return;
        }

        if($club->exists($name)){
            $fee = 0;
        }else{
            $fee = 100;
        }

        switch($data){
            case 0:
                $shopData = ConfigBase::getFor(ConfigList::PURCHASE)->get("stone");
            break;
            case 1:
                $shopData = $shop->get("閃緑岩");
            break;
            case 2:
                $shopData = $shop->get("安山岩");
            break;
            case 3:
                $shopData = $shop->get("花崗岩");
            break;
        }

        $player->sendForm(new CountForm($shopData));

    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'公式ショップ',
            'content'=>'購入したいものを選択してください',
            'buttons'=>[
                [
                    'text'=>'石'
                ],
                [
                    'text'=>'閃緑岩'
                ],
                [
                    'text'=>'安山岩'
                ],
                [
                    'text'=>'花崗岩'
                ]
            ]
        ];
    }
}


class WoodForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        $name = $player->getName();
        $shop = ConfigBase::getFor(ConfigList::PURCHASE);
        $club = ConfigBase::getFor(ConfigList::CLUB);

        if($data === null){
            return;
        }

        if($club->exists($name)){
            $fee = 0;
        }else{
            $fee = 100;
        }

        switch($data){
            case 0:
                $shopData = $shop->get("oka");
            break;
            case 1:
                $shopData = $shop->get("spruce");
            break;
            case 2:
                $shopData = $shop->get("birch");
            break;
            case 3:
                $shopData = $shop->get("jungle");
            break;
            case 4:
                $shopData = $shop->get("acacia");
            break;
            case 5:
                $shopData = $shop->get("dark_oka");
            break;
        }

        $player->sendForm(new CountForm($shopData));

    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'公式ショップ',
            'content'=>'購入したいものを選択してください',
            'buttons'=>[
                [
                    'text'=>'オークの原木'
                ],
                [
                    'text'=>'マツの原木'
                ],
                [
                    'text'=>'シラカバの原木'
                ],
                [
                    'text'=>'ジャングルの原木'
                ],
                [
                    'text'=>'アカシアの原木'
                ],
                [
                    'text'=>'ダークオークの原木'
                ]
            ]
        ];
    }
}
?>