<?php 

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\utils\Config;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Form\Government\PurchaseForm;
use plugin\Form\Government\GovernmentDepositBalance;
use plugin\Form\Government\GovernmentOfficial;


class GovernmentMenu implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }

        $purchaseConfig = ConfigBase::getFor(ConfigList::PERCHASE);
        $itemData = $purchaseConfig->get("stone");
        
        if($data[1]>$itemData['max-count']){
            $player->sendMessage("§a[個人通知] §7買取上限数を超えています");
        }else{
            $player->sendForm(new PurchaseConfirmation($data[1],$itemData));
        }
    }

    public function jsonSerialize(){
        $purchaseConfig = ConfigBase::getFor(ConfigList::PERCHASE);
        $itemData = $purchaseConfig->get("stone");
        return[
            'type'=>'custom_form',
            'title'=>'資源買取フォーム',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'type'=>'label',
                    'text'=>'現在は'.$itemData['name'].'を'.$itemData['max-count'].'個の買取を行っております。それ以外のアイテムは現在買取は行っておりません。'
                ],
                [
                    'type'=>'slider',
                    'text'=>'買取希望個数',
                    'min'=>1,
                    'max'=>$itemData['max-count'],
                    'default'=>1
                ]
            ]
        ];
    }
}


class PurchaseConfirmation implements Form{

    public function __construct($count,$itemData){
        $this->count = $count;
        $this->itemData = $itemData;
    }

    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        

        foreach ($player->getInventory()->getContents() as $item){
			if($this->itemData['id']==$item->getId()){
					$itemCount += $item->getCount();
			}
        }
        

        $item=Item::get($itemData['id'],0,$this->count);
        if($itemCount >= $this->count){
            $player->getInventory()->removeItem($item);
            $purchaseConfig = ConfigBase::getFor(ConfigList::PERCHASE);
            $purchaseConfig->set("stone"."max-count",$this->itemData['max-count']-$this->count);
            $purchaseConfig->save();
        }else{
            $player->sendMessage("§a[個人通知] §7買取希望個数を下回っています");
        }
    }

    public function jsonSerialize(){
        $totalPrice = $this->itemData['price']*$this->count;
        return[
            'type'=>'custom_form',
            'title'=>'資源買取フォーム',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'type'=>'label',
                    'text'=>'買取品目:'.$this->$itemData['name']."\n買取値段:".$this->itemData['price']."/一個\n買取予定数:".$this->count."個\n買取合計金額:".$totalPrice.'円'
                ],
                [
                    'type'=>'label',
                    'text'=>'こちらで間違いがなければ送信ボタンを押してください'
                ]
            ]
        ];
    }
}
?>