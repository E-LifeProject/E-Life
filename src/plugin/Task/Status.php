<?php

namespace plugin\Task;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;


class Status extends Task{

    public function __construct($api){
        $this->api = $api;
    }

    public function onRun($tick){
        //Player全員にTipを常時表示させる
        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            $money = $this->api->getInstance()->myMoney($player);
            $echoManey=sprintf("%'-8d",$money); //ハイフンで桁を埋める
            $player->sendTip("\n§bE-Life鯖  §f所持金:".$echoManey."円\n §7現在時刻:".date("Y-m/d H:i"));
        }
    }
}
?>