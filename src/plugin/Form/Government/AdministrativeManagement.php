<?php 

namespace plugin\Form\Government;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Form\Government\Purchase;
use plugin\Form\Government\GovernmentDepositBalance;
use plugin\Form\Government\GovernmentOfficial;
use plugin\Form\Government\AdministrativeManagement;
use plugin\Form\Government\GovernmentManagement;
use plugin\Form\Government\ItemChange;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class AdministrativeManagement implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        switch($data){

            //政府管理
            case 0:
                if($player->isOp()){
                    $player->sendForm(new GovernmentManagement());
                }else{
                    $player->sendMessage("§a[個人通知] §7あなたは政府役人ではありません");
                }
            break;

        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'政府系フォーム',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'text'=>'政府管理'
                ]
            ]
        ];
    }
}

class GovernmentManagement implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        switch($data){

            //政府買取品目変更
            case 0:
                $player->sendForm(new ItemChange());
            break;

        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'政府系フォーム',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'text'=>'買取品目変更'
                ]
            ]
        ];
    }

}


class ItemChange implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        switch($data[0]){

            //石
            case 0:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","stone");
                $config->save();
            break;

            case 1:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","oka");
                $config->save();
            break;

            case 2:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","spruce");
                $config->save();
            break;

            case 3:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","birch");
                $config->save();
            break;

            case 4:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","jungle");
                $config->save();
            break;

            case 5:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","acacia");
                $config->save();
            break;

            case 6:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","dark_oka");
                $config->save();
            break;
            
            case 7:
                $config = ConfigBase::getFor(ConfigList::PURCHASE);
                $config->set("setItem","ironOre");
                $config->save();
            break;
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'政府買取品目変更',
            'content'=>[
                [
                    'type'=>'dropdown',
                    'text'=>'買取品目を選択してください',
                    'options'=>[
                        '石',
                        'オークの原木',
                        'マツの原木',
                        'シラカバの原木',
                        'ジャングルの原木',
                        'アカシアの原木',
                        'ダークオークの原木',
                        '鉄鉱石'
                    ],
                    'default'=>2
                ]
            ]
        ];
    }

}
?>