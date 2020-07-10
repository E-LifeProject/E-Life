<?php

namespace bank\Form;

#Basic
use pocketmine\Player;
use pocketmine\form\Form;

use bank\Main;
use bank\BankItem;
use bank\Form\Passbook\PassbookCheck;
use bank\Form\Passbook\ChangePassword;
use bank\Form\Passbook\ReissuePassbook;
use bank\Form\Money\Deposit;
use bank\Form\Money\Drawer;
use bank\Form\Money\CheckMoney;
use bank\Form\Money\Debt\DebtMenu;

class MainMenu implements Form{
	public function __construct(Main $main){
		$this->main = $main;
        $this->item = new BankItem($this->main);
	}

    public function handleResponse(Player $player, $data):void{
        $passcard_check = $this->item->checkPasscard($player);
        $inv = $player->getInventory();
        if($data === null){
            return;
        }
        switch($data){
        	case 0:
                if(is_null($passcard_check)){
            	    $player->sendForm(new PassbookCheck($this->main));
                } else {
                    $player->sendMessage("§l§c>通帳を既に持っています<");
                }
        		break;

            case 1:
                if(is_null($passcard_check)){
                    $player->sendForm(new ReissuePassbook($this->main));
                } else {
                    $player->sendMessage("§l§c>通帳を既に持っています<");
                }
                break;       

        	case 2:
                if(!is_null($passcard_check)){
                    $player->sendForm(new Deposit($this->main));
                } else {
                    $player->sendMessage("§l§c>通帳がありません<");
                }
        		break;

        	case 3:
                if(!is_null($passcard_check)){
                    $player->sendForm(new Drawer($this->main));
                } else {
                    $player->sendMessage("§l§c>通帳がありません<");
                }
        		break;

        	case 4:
                if(!is_null($passcard_check)){
                    $player->sendForm(new ChangePassword($this->main));
                } else {
                    $player->sendMessage("§l§c>通帳がありません<");
                }
        		break;

        	case 5:
                if(!is_null($passcard_check)){
                    $player->sendForm(new CheckMoney($this->main->getMoney($player->getName()), $this->main->getDebt($player->getName())));
                } else {
                    $player->sendMessage("§l§c>通帳がありません<");
                }
        		break;

            case 6:
                if(!is_null($passcard_check)){
                    $player->sendForm(new DebtMenu($this->main));
                } else {
                    $player->sendMessage("§l§c>通帳がありません<");
                }
                break;
        }
    }

    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'E-LifeBank',
            'content'=>'選択してください',
            'buttons'=>[
                [
                    'text'=>'通帳発行',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/creative_icon'
                    ]
                ],  
                [
                    'text'=>'通帳再発行',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/icon_book_writable'
                    ]
                ],   
                [
                    'text'=>'預入',
                    'image'=>[
                    'type'=>'path',
                    'data'=>'textures/ui/download_backup'
                    ]
                ],   
                [
                    'text'=>'引出',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/backup_replace'
                    ]
                ],
                [
                	'text'=>'パスワード変更',
                	'image'=>[
                		'type'=>'path',
                		'data'=>'textures/ui/accessibility_glyph'
                	]
                ],
                [
                    'text'=>'残高確認',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/MCoin'
                    ]
                ],
                [
                    'text'=>'借金',
                    'image'=>[
                        'type'=>'path',
                        'data'=>'textures/ui/icon_blackfriday'
                    ]
                ]
            ]
        ];
    }
}