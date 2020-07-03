<?php

namespace plugin\Form\Shop;

#Basic
use pocketmine\form\Form;
use pocketmine\Player;

#Form
use plugin\Form\Shop\ConfirmationForm;



class ShopForm implements Form{

    public function __construct($main){
        $this->main = $main;
    }

    //Formの処理
    public function handleResponse(Player $player,$data):void{
        $name = $player->getName();
        /**
         * もしshop.ymlに登録されているアイテムなら
         * 購入確認Formへ
         * 
         * 登録されていないアイテムならErrorを表示
         */
        if($data === null){
            return;
        }elseif($this->main->shop->exists($data[0])){//登録されているアイテムの処理
            $shopData = $this->main->shop->get($data[0]);
            if($this->main->club->exists($name)){
                $fee =0;
                $total = $shopData['price']*$data[1];
            }else{
                $fee = 100;
                $total = $shopData['price']*$data[1]+$fee;
            }
            $player->sendForm(new ConfirmationForm($shopData['name'],$shopData['price'],$shopData['id'],$data[1],$total,$fee));
        }elseif(!$this->main->shop->exists($data[0])){//登録されていないアイテムの処理（入力ミスもこの処理)
            $player->sendPopUp("§a通知>>そのブロックやアイテムは取り扱ってません\n\n");
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'公式ショップ',
            'content'=>[
                [
                    'type'=>'input',
                    'text'=>'アイテムid',
                    'placeholder'=>'購入したいアイテムidを入力してください'
                ],
                [
                    'type'=>'slider',
                    'text'=>'個数',
                    'min'=>1,
                    'max'=>64,
                    'default'=>10
                ]
            ]
        ];
    }
}
?>