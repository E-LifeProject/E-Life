<?php 

namespace plugin\Form\Government;

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
use plugin\Economy\MoneyListener;
use plugin\Economy\Government\Storehouse;


/**
 * 買取商品は政府関係者（OP)が設定で変更できる
 * 政府の資源保管庫の状況を見て
 * 買取品目を変更していく
 */

class Purchase implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }

        $player->sendForm(new PurchaseConfirmation($data[1],$this->itemData));
    }

    public function jsonSerialize(){
        $config = ConfigBase::getFor(ConfigList::PURCHASE);
        $this->itemData = $config->get($config->get("setItem"));
        return[
            'type'=>'custom_form',
            'title'=>'資源買取フォーム',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>'現在買取アイテム: '.$this->itemData['jpnName']."\n上記以外のアイテムは現在買取は行っておりません。"
                ],
                [
                    'type'=>'slider',
                    'text'=>'買取希望個数',
                    'min'=>1,
                    'max'=>64,
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
        $this->totalPrice = $itemData['price']*$this->count;
    }

    public function handleResponse(Player $player,$data):void{
        
        if($data === null){
            return;
        }
        
        $haveCount = 0;
        foreach ($player->getInventory()->getContents() as $item){
			if($this->itemData['id' ] == $item->getId()){
                if($this->itemData['damage'] == $item->getDamage()){
					$haveCount += $item->getCount();
				}
			}
        }
        $item=Item::get($this->itemData['id'],$this->itemData['damage'],$this->count);
        if($haveCount >= $this->count){
            $player->getInventory()->removeItem($item);
            Storehouse::getInstance()->addItemCount($this->itemData["name"],$this->count);
            $money_instance = new MoneyListener($player->getName());
            $money_instance->addMoney($this->totalPrice);
            $player->sendMessage("§a[個人通知] §7買取完了しました");
        }else{
            $player->sendMessage("§a[個人通知] §7買取希望個数を下回っています");
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'資源買取フォーム',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>'買取品目:'.$this->itemData['jpnName']."\n買取数:".$this->count."個\n買取値段:".$this->itemData['price']."円/個\n--------------------\n買取合計金額:".$this->totalPrice.'円'
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