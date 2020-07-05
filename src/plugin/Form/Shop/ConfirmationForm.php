<?php

namespace plugin\Form\Shop;

#Basic 
use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\item\Item;

#EconomyAPI
use onebone\economyapi\EconomyAPI;


class ConfirmationForm implements Form{

	private $total;
	private $number;
	private $id;
	private $name;
	private $fee;

	public function __construct($name, $price, $id, $number, $total, $fee){
        $this->name = $name;
        $this->price = $price;
        $this->id = $id;
        $this->number = $number;
        $this->total = $total;
        $this->fee = $fee;
    }

    /**
     * E-Club加入者には送料を無料にする
     * 加入者以外は送料一律100円
     */
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }elseif(EconomyAPI::getInstance()->myMoney($player)<$this->total){//代金を支払えない場合の処理
            $player->sendPopUp("§a通知>>合計金額を払うことが出来ません\n\n");
        }elseif(EconomyAPI::getInstance()->myMoney($player)>=$this->total){//代金を支払える場合の処理
            //inventoryがいっぱいの時の処理を書かなければいけない
            EconomyAPI::getInstance()->reduceMoney($player,$this->total);
            $item = Item::get($this->id,0,$this->number);
            $player->getInventory()->addItem($item);
            $player->sendPopUp("§a通知>>購入しました\n\n");
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'確認フォーム',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"商品名:".$this->name."\n購入数:".$this->number."個\n手数料:".$this->fee."円\n--------------------\n合計金額:".$this->total."円です\n \n§7購入する場合は送信、購入をキャンセルする場合は右上の×を押してください。また購入後のキャンセルは出来ません。"

                ]
            ]
        ];
    }
}
?>