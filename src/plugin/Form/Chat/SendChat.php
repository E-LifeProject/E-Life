<?php

namespace plugin\form\chat;

#Basic 
use pocketmine\Player;
use pocketmine\form\Form;

class SendChat implements Form{

	private $money;

	public function __construct($money){
        $this->money = $money;
    }


    //Formの処理
    public function handleResponse(Player $player,$data): void{
        if($data === null){
            return;
        }
    }
    
    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'チャットの送信',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"所持金: ".$this->money."円\n送信料: 100円/通\n "
                ],
                [
                    'type'=>'input',
                    'text'=>'送信先の名前'
                ],
                [
                    'type'=>'input',
                    'text'=>'本文',
                    'placeholder'=>'本文を入力してください'
                ]
            ]
        ];
    }
}
?>