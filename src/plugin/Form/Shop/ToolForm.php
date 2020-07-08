<?php

namespace plugins\Form\Shop;

#Basic
use pocketmine\form\Form;
use pocketmine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


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
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                
            break;
            case 1:
                
            break;
            case 2:
               
            break;
            case 3:
                
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
                    'text'=>'木のピッケル'
                ],
                [
                    'text'=>'石のピッケル'
                ],
                [
                    'text'=>'鉄のピッケル'
                ],
                [
                    'text'=>'ダイヤのピッケル'
                ]
            ]
        ];
    }
}

class AxeForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                
            break;
            case 1:
                
            break;
            case 2:
               
            break;
            case 3:
                
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
                    'text'=>'木の斧'
                ],
                [
                    'text'=>'石の斧'
                ],
                [
                    'text'=>'鉄の斧'
                ],
                [
                    'text'=>'ダイヤの斧'
                ]
            ]
        ];
    }
}

class ShovelForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                
            break;
            case 1:
                
            break;
            case 2:
               
            break;
            case 3:
                
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
                    'text'=>'木のシャベル'
                ],
                [
                    'text'=>'石のシャベル'
                ],
                [
                    'text'=>'鉄のシャベル'
                ],
                [
                    'text'=>'ダイヤのシャベル'
                ]
            ]
        ];
    }
}

class HoeForm implements Form{
    
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }

        switch($data){
            case 0:
                
            break;
            case 1:
                
            break;
            case 2:
               
            break;
            case 3:
                
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
                    'text'=>'木のクワ'
                ],
                [
                    'text'=>'石のクワ'
                ],
                [
                    'text'=>'鉄のクワ'
                ],
                [
                    'text'=>'ダイヤのクワ'
                ]
            ]
        ];
    }
}
?>