<?php

namespace plugin\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Form\Bank\WithdrawMoney;
use plugin\Form\Bank\MoneyDeposit;
use plugin\Form\Bank\CashTransfer;
use plugin\Form\Bank\Loan;
use plugin\Form\Bank\AccountOpening;


class BankMenu implements Form{

    public function handleResponse(Player $player,$data):void{
        
        if($data === null){
            return;
        }

        /**
         * 銀行関連のFormを送る前に
         * 口座があるか判定してから送信する
         */

        switch($data){
            //お引き出し
            case 0:
                if(Bank::getInstance()->checkAccount($player->getName())){
                    $player->sendForm(new WithdrawMoney($player->getName()));
                }else{
                    $player->sendMessage("§a[個人通知] §7口座が開設されておりません");
                }
            break;

            //お預入れ
            case 1:
                if(Bank::getInstance()->checkAccount($player->getName())){
                    $player->sendForm(new MoneyDeposit($player->getName()));
                }else{
                    $player->sendMessage("§a[個人通知] §7口座が開設されておりません");
                }
            break;


            /**
             * 所持金と口座両方から振り込みできるように
             * その為、銀行口座の判定はこの段階では必要ない
             */

            //お振込
            case 2:
                $player->sendForm(new CashTransfer($player->getName()));
            break;

            //ローン関係
            case 3:
                if(Bank::getInstance()->checkAccount($player->getName())){
                    $player->sendForm(new Loan());
                }else{
                    $player->sendMessage("§a[個人通知] §7口座が開設されておりません");
                }
            break;

            //口座開設
            case 4:
                if(Bank::getInstance()->checkAccount($player->getName())){
                    $player->sendMessage("§a[個人通知] §7既に口座が開設されております");
                }else{
                    $player->sendForm(new AccountOpening());
                }
            break;
        }
    }


    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'銀行ATM',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'text'=>'お引き出し'
                ],
                [
                    'text'=>'お預入れ'
                ],
                [
                    'text'=>'お振込み'
                ],
                [
                    'text'=>'ローン関係'
                ],
                [
                    'text'=>'口座開設'
                ]
            ]
        ];
    }
}