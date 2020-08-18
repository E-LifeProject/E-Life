<?php

namespace plugin\Form\Admin;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Economy\Bank;

class LoanReview implements Form{

    public function handleResponse(Player $player, $data) : void{
		if($data === null){
			return;
        }
        var_dump($this->data[$data]);
        var_dump($this->name[$data]);

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

        switch($data[1]){
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

        return[
            'type'=>'custom_form',
            'title'=>'管理者用メニュー',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"現在の銀行資金:".$bank->getBankMoney()."円\nローン契約者:".$this->name."さん\nローン予定金額:".$this->data[$this->name]."円\nこのローンを承認する場合は下の選択肢から選択してください"
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