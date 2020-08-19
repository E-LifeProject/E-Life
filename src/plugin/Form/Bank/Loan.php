<?php

namespace plugin\Form\Bank;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\utils\Config;

#E-Life
use plugin\Economy\Bank;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

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
                        $player->sendMessage("§a[個人通知] §7ローンがまだ残っている為新規申し込みはできません");
                    }else{
                        if($bank->checkApplicationLoan($player->getName())){
                            $player->sendMessage("§a[個人通知] §7ローンの申請が終了するまでしばらくお待ちください");
                        }else{
                            if(ConfigBase::getFor(ConfigList::PENALTY)->exists($player->getName())){
                                $player->sendMessage("§a[個人通知] §7あなたはローンを申込む事が出来ません");
                            }else{
                                $player->sendForm(new ApplyLoan());
                            }
                        }
                    }
            break;

            //ローンの返済
            case 1: 
                if($bank->checkLoan($player->getName())){
                    $player->sendForm(new RepaymentLoan($player->getName()));
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

        switch($data[0]){
            case 0:
                $reason = "土地購入";
            break;

            case 1:
                $reason = "住宅建設";
            break;
        }
        $bank->applicationLoan($player->getName(),$count*10000,$reason);
        $player->sendMessage("§a[個人通知] §7ローンを申し込みました。審査が完了するまでしばらくお待ちください");
    }
    
    public function jsonSerialize(){
        $rate = Bank::getInstance()->getgetInterestRate()*100;
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
                ],
                [
                    'type'=>'label',
                    'text'=>"ローン金利:".$rate."%\nローンの返済期限は審査が完了してから20日です。その為、申し込み後はこまめに審査が完了しているか確認してください"
                ]
            ]
        ];
    }
}

class RepaymentLoan implements Form{
    
    public function __construct($name){
        $this->name = $name;
    }

    public function handleResponse(Player $player,$data):void{
        $bank = Bank::getInstance();

        if($data === null){
            return;
        }
        if(!is_numeric($data[1]) || $data[1] === ""){
            $player->sendMessage("§a[個人通知] §7数字を入力してください");
            return;
        }
        //ローンは銀行口座から支払うようにする(所持金からの支払いは現時点では不可)
        if($bank->getDepositBalance($player->getName()) >= intval($data[1])){
            if($bank->getLoan($player->getName()) > intval($data[1])){
                $bank->repaymentLoan($player->getName(),intval($data[1]));
                $bank->reduceDepositBalance($player->getName(),intval($data[1]));
                $player->sendMessage("§a[個人通知] §7ローンを返済しました");
            }elseif($bank->getLoan($player->getName()) == intval($data[1])){
                $bank->repaymentLoan($player->getName(),intval($data[1]));
                $bank->reduceDepositBalance($player->getName(),intval($data[1]));
                ConfigBase::getFor(ConfigList::BANK_ACCOUNT)->setNested($player->getName().".Loan.Date",0);
                ConfigBase::getFor(ConfigList::BANK_ACCOUNT)->setNested($player->getName().".Loan.Reason",0);
                ConfigBase::getFor(ConfigList::BANK_ACCOUNT)->save();
                $player->sendMessage("§a[個人通知] §7ローンを返済し終わりました");
            }else{
                $player->sendMessage("§a[個人通知] §7ローン残高よりも返済希望額が上回っている為返済できませんでした");
            }
        }
    }
    
    public function jsonSerialize(){
        $bank = Bank::getInstance();

        return[
            'type'=>'custom_form',
            'title'=>'ローンのご返済',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>'残りローン返済額:'.$bank->getLoan($this->name)."円\nローン返済期日:".$bank->getLoanDate($this->name)."日"
                ],
                [
                    'type'=>'input',
                    'text'=>'ローン返済金額',
                ]
            ]
        ];
    }
}