<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;

class Loan implements Form{
    public function handleResponse(Player $player,$data):void{
        $bank = Bank::getInstance();

        if($data === null){
            return;
        }

        switch($data){

            //新規申し込み
            case 0:
                if($bank->checkLoan($player->getName())){
                    $player->sendMessage("§a[個人通知] §7現在ローンの返済が残っている為新規申し込みは出来ません");
                }else{
                    if($bank->checkApplicationLoan($player->getName())){
                        $player->sendMessage("§a[個人通知] §7現在ローンの申請中です。審査が完了するまでしばらくお待ちください");
                    }else{
                        $player->sendForm(new ApplyLoan());
                    }
                }
            break;

            //ローンの返済
            case 1: 
                if($bank->checkLoan($player->getName())){
                    $player->sendForm(new RepaymentLoan());
                }else{
                    $player->sendMessage("§a[個人通知] §7ローンのお申し込みはありません");
                }
            break; 
        }
    }
    
    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'ローン',
            'content'=>'実行したいものを選択してください',
            'buttons'=>[
                [
                    'text'=>'ローンのお申し込み'
                ],
                [
                    'text'=>'ローンの返済'
                ]
            ]
        ];
    }
}

class ApplyLoan implements Form{
    public function handleResponse(Player $player,$data):void{
        $bank = Bank::getInstance();

        if($data === null){
            return;
        }
        $count = $data[1]+1;
        $count *= 10;
        $bank->applicationLoan($player->getName(),$count*10000);
    }
    
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'ローンのお申し込み',
            'content'=>[
                [
                    'type'=>'dropdown',
                    'text'=>'ローン利用用途',
                    'options'=>[
                        '土地購入',
                        '住宅建設'
                    ],
                    'default'=> 1
                ],
                [
                    'type'=>'step_slider',
                    'text'=>'ローン希望金額(/万)',
                    'steps'=>['10','20','30','40','50'],
                    'default'=>2
                ]
            ]
        ];
    }
}