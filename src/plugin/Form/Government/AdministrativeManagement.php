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

        $config = ConfigBase::getFor(ConfigList::PURCHASE);
        switch($data[0]){

            //石
            case 0:
                $config->set("setItem","stone");
                $config->save();
            break;

            //オークの原木
            case 1:
                $config->set("setItem","oka");
                $config->save();
            break;

            //マツの原木
            case 2:
                $config->set("setItem","spruce");
                $config->save();
            break;

            //シラカバの原木
            case 3:
                $config->set("setItem","birch");
                $config->save();
            break;

            //ジャングルの原木
            case 4:
                $config->set("setItem","jungle");
                $config->save();
            break;

            //アカシアの原木
            case 5:
                $config->set("setItem","acacia");
                $config->save();
            break;

            //ダークオークの原木
            case 6:
                $config->set("setItem","dark_oka");
                $config->save();
            break;
            
            //鉄鉱石
            case 7:
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