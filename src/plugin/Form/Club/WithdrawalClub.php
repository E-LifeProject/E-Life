<?php

namespace plugin\Form\Club;

#Basic
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\utils\Config;

class WithdrawalClub implements Form　{

	/**　@var Config|null */
	private $club;

	public function __construct($club){
        $this->club = $club = ConfigBase::getFor(ConfigList::CLUB);;
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
            $player->sendPopUp("§a通知>>E-Clubを退会しました\n\n");
        }else{
            $player->sendPopUp("§a通知>>退会をキャンセルしました\n\n");
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
                    'text'=>"退会する場合はしたのボタンを押してください。退会されても返金はできませんのでご了承ください。\n--------------------"
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