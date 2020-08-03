<?php 

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life


class governmentMenu implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }
        switch($data){
            case 0:
                $player->sendForm();
            break;

            case 1:
                $player->sendForm();
            break;

            case 2:
                $player->sendForm();
            break;

            case 3:
                $player->sendForm();
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
                    'text'=>'ブロック買取'
                ],
                [
                    'text'=>'国庫金の確認'
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