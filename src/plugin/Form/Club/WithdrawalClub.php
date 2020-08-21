<?php

namespace plugin\Form\Club;

#Basic
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\utils\Config;

class WithdrawalClub implements Form{

	/**　@var Config|null */
	private $club;

	public function __construct(){
        $this->club = ConfigBase::getFor(ConfigList::CLUB);;
    }

    //Formの処理
    public function handleResponse(Player $player,$data):void{
        $name = $player->getName();

        if($data === null){
            return;
        }
        if($data[1] === true){
            $this->club->__unset($name);
            $this->club->save();
            $player->sendMessage("§a[個人通知] §7E-Clubを退会しました");
        }else{
            $player->sendMessage("§a[個人通知] §7E-Clubの退会手続きをキャンセルしました");
        }
    }

    //表示するForm
    public function jsonSerialize(){
        return[
            'type'=>'custom_form',
            'title'=>'E-Club退会',
            'content'=>[
                [
                    'type'=>'label',
                    'text'=>"退会する場合は下のボタンを押してください。退会されても返金はできませんのでご了承ください。\n--------------------"
                ],
                [
                    'type'=>'toggle',
                    'text'=>'§7退会する',
                    'default'=>false
                ]
            ]
        ];
    }
}
?>