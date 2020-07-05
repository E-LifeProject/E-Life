<?php

namespace plugin\Form\Shop;

#Basic
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\form\Form;
use pocketmine\Player;

#Form

class ShopForm implements Form{

    //Formの処理
    public function handleResponse(Player $player,$data):void{
	    /**
         * もしshop.ymlに登録されているアイテムなら
         * 購入確認Formへ
         *
         * 登録されていないアイテムならErrorを表示
         */
	    if($data === null) {
		    return;
	    }

	    $name = $player->getName();
	    $shop = ConfigBase::getFor(ConfigList::SHOP);
	    $club = ConfigBase::getFor(ConfigList::CLUB);
	    if($shop->exists($data[0])){//登録されているアイテムの処理
            $shopData = $shop->get($data[0]);
            if($club->exists($name)){
                $fee =0;
                $total = $shopData['price']*$data[1];
            }else{
                $fee = 100;
                $total = $shopData['price']*$data[1]+$fee;
            }
            $player->sendForm(new ConfirmationForm($shopData['name'],$shopData['price'],$shopData['id'],$data[1],$total,$fee));
        }elseif(!$shop->exists($data[0])){//登録されていないアイテムの処理（入力ミスもこの処理)
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