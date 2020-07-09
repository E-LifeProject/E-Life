<?php

namespace plugins\Form\Shop;

#Basic
use pocketmine\form\Form;
use pocketmine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Form\Shop\ConfirmationForm;


class ToolForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                $player->sendForm(new PickaxeForm());
            break;
            case 1:
                $player->sendForm(new AxeForm());
            break;
            case 2:
                $player->sendForm(new ShovelForm());
            break;
            case 3:
                $player->sendForm(new HoeForm());
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
                    'text'=>'ツルハシ'
                ],
                [
                    'text'=>'斧'
                ],
                [
                    'text'=>'シャベル'
                ],
                [
                    'text'=>'クワ'
                ]
            ]
        ];
    }
}


class PickaxeForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        $name = $player->getName();
        $shop = ConfigBase::getFor(ConfigList::SHOP);
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
                $shopData = $shop->get("木のピッケル");
            break;
            case 1:
                $shopData = $shop->get("石のピッケル");
            break;
            case 2:
                $shopData = $shop->get("鉄のピッケル");
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
                    'text'=>'木のピッケル'
                ],
                [
                    'text'=>'石のピッケル'
                ],
                [
                    'text'=>'鉄のピッケル'
                ]
            ]
        ];
    }
}

class AxeForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        $name = $player->getName();
        $shop = ConfigBase::getFor(ConfigList::SHOP);
        $club = ConfigBase::getFor(ConfigList::CLUB);

        if($data === null){
            return;
        }

        switch($data){
            case 0:
                $shopData = $shop->get('木の斧');
            break;
            case 1:
                $shopData = $shop->get('石の斧');
            break;
            case 2:
                $shopData = $shop->get('鉄の斧');
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
                    'text'=>'木の斧'
                ],
                [
                    'text'=>'石の斧'
                ],
                [
                    'text'=>'鉄の斧'
                ]
            ]
        ];
    }
}

class ShovelForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        $name = $player->getName();
        $shop = ConfigBase::getFor(ConfigList::SHOP);
        $club = ConfigBase::getFor(ConfigList::CLUB);

        if($data === null){
            return;
        }

        switch($data){
            case 0:
                $shopData = $shop->get('木のシャベル');
            break;
            case 1:
                $shopData = $shop->get('石のシャベル');
            break;
            case 2:
                $shopData = $shop->get('鉄のシャベル');
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
                    'text'=>'木のシャベル'
                ],
                [
                    'text'=>'石のシャベル'
                ],
                [
                    'text'=>'鉄のシャベル'
                ]
            ]
        ];
    }
}

class HoeForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        $name = $player->getName();
        $shop = ConfigBase::getFor(ConfigList::SHOP);
        $club = ConfigBase::getFor(ConfigList::CLUB);

        if($data === null){
            return;
        }

        switch($data){
            case 0:
                $shopData = $shop->get('木のクワ');
            break;
            case 1:
                $shopData = $shop->get('石のクワ');
            break;
            case 2:
                $shopData = $shop->get('鉄のクワ');
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
                    'text'=>'木のクワ'
                ],
                [
                    'text'=>'石のクワ'
                ],
                [
                    'text'=>'鉄のクワ'
                ]
            ]
        ];
    }
}
?>