<?php 

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Form\Government\Purchase;
use plugin\Form\Government\GovernmentDepositBalance;
use plugin\Form\Government\GovernmentOfficial;
use plugin\Form\Government\AdministrativeManagement;

class GovernmentMenu implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        switch($data){

            //政府ブロックorアイテム買取
            case 0:
                $player->sendForm(new Purchase());
            break;

            //政府の預金残高を照会（国庫)
            case 1:
                $player->sendForm(new GovernmentDepositBalance());
            break;

            //権限者の確認
            case 2:
                $player->sendForm(new GovernmentOfficial());
            break;

            //市町村別の財政を管理したり、政府関係者が市町村の管理をする
            case 3:
                $player->sendForm(new AdministrativeManagement());
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
                    'text'=>'資源買取フォーム'
                ],
                [
                    'text'=>'政府預金残高照会'
                ],
                [
                    'text'=>'政府関係者リスト'
                ],
                [
                    'text'=>'行政管理(関係者のみ)'
                ]
            ]
        ];
    }
}
?>