<?php 

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;


class governmentMenu implements Form{
    public function handleResponse(Player $player,$data):void{

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
                    'text'=>'国庫残高確認'
                ],
                [
                    'text'=>'市町村残高確認'
                ],
                [
                    'text'=>'行政管理(操作権限がある方のみ)'
                ]
            ]
        ];
    }
}
?>