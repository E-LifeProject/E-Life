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
	private $count;
	private $id;
	private $shopData;
	private $fee;

	public function __construct($shopData,$count,$total,$fee){
        $this->shopData = $shopData;
        $this->count = $count;
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
            $player->sendMessage("§a[個人通知] §7所持金が足りません");
        }elseif(EconomyAPI::getInstance()->myMoney($player)>=$this->total){//代金を支払える場合の処理
            //inventoryがいっぱいの時の処理を書かなければいけない
            EconomyAPI::getInstance()->reduceMoney($player,$this->total);
            $item = Item::get($this->shopData['id'],0,$this->count);
            $player->getInventory()->addItem($item);
            $player->sendMessage("§a[個人通知] §7購入しました");
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'確認フォーム',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"商品名:".$this->shopData['name']."\n購入数:".$this->count."個\n手数料:".$this->fee."円\n--------------------\n合計金額:".$this->total."円です\n \n§7購入する場合は送信、購入をキャンセルする場合は右上の×を押してください。また購入後のキャンセルは出来ません。"

                ]
            ]
        ];
    }
}
?>