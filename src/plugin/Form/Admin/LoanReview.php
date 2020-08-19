<?php

namespace plugin\Form\Admin;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;
use plugin\Economy\MoneyListener;

class LoanReview implements Form{

    public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
        }
        $player->sendForm(new LoanDetails($this->name[$data],$this->data[$data]));
	}

	//表示するForm
    public function jsonSerialize(){
        $bank = Bank::getInstance();
        $this->loans = $bank->getApplicationLoan();

        foreach($this->loans as $key => $value){
           $this->buttons[] = ['text'=>$key."さん".$value."円"];
           $this->name[]= $key;
           $this->data[] = array($key => $value);
        }

        return[
            'type'=>'form',
            'title'=>'管理者用メニュー',
            'content'=>'実行したい項目を選んでください',
            'buttons'=>$this->buttons
        ];
    }
}


class LoanDetails implements Form{

    public function __construct($name,$data){
        $this->name = $name;
        $this->data = $data;
    }
    
    public function handleResponse(Player $player, $data) : void{
        $bank = Bank::getInstance();

		if($data === null){
			return;
        }

        switch($data[3]){
            case 0:
                if($bank->getBankMoney() >= $this->data[$this->name]){
                    $bank->addLoan($player->getName(),$this->data[$this->name]);
                    $player->sendMessage("§a[個人通知] §7ローンの申請を許可しました");
                }else{
                    $player->sendMessage("§a[個人通知] §7ローンの金額を銀行側が支払うことが出来ません");
                }
            break;

            case 1:
                $bank->rejecteLoan($player->getName());
                $player->sendMessage("§a[個人通知] §7ローンの申請を却下しました");
            break;
        }

        
	}


    public function jsonSerialize(){
        $bank = Bank::getInstance();
        $money_instance = new MoneyListener($this->name);
        $money = $money_instance->getMoney();

        return[
            'type'=>'custom_form',
            'title'=>'管理者用メニュー',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"【契約内容】\n契約者:".$this->name."さん\n契約額:".$this->data[$this->name]."円"
                ],
                [
                    'type'=>'label',
                    'text'=>"【契約者情報】\nプレイ時間:----\n所持金:".$money."円\n銀行預金残高:".$bank->getDepositBalance($this->name)."円"
                ],
                [
                    'type'=>'label',
                    'text'=>"【貸し出し状況】\n銀行資金".$bank->getBankMoney()."円"
                ],
                [
                    'type'=>'dropdown',
                    'text'=>'選択してください',
                    'options'=>[
                        'ローンを実行する',
                        'ローンを却下する'
                    ],
                ]
            ]
        ];
    }
}