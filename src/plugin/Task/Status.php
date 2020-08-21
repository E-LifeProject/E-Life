<?php

namespace plugin\Task;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;

#E-Life
use plugin\Economy\MoneyListener;


class Status extends Task{

    public function onRun($tick){
        //Player全員にTipを常時表示させる
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $name = $player->getName();
            $money_instance = new MoneyListener($name);
            $money = $money_instance->getMoney();
            $echoManey=sprintf("%'-6d",$money); //ハイフンで桁を埋める
            $player->sendTip("\n§bE-Life鯖 §f所持金: ".$echoManey."円\n  §7時刻: ".date("Y-m/d H:i"));
        }
    }
}
