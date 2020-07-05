<?php

namespace plugin;

#Basic
use plugin\Config\ConfigBase;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

#E-Life
use plugin\Event\Event;
use plugin\Task\Status;
use plugin\Task\Club;

#EconomyAPI
use onebone\economyapi\EconomyAPI;


class Main extends PluginBase implements Listener{

    public function onEnable(): void {

        date_default_timezone_set('asia/tokyo');

        $this->getServer()->loadLevel("nature");

        ConfigBase::init($this);

        //EconomyAPIを読み込む
        $api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if($api == null){
            $this->getLogger()->error("EconomyAPIを読み込むことが出来ませんでした");
            $this->getServer()->shutdown();
        }else{
            $this->getLogger()->info("EconomyAPIを読み込みました");
        }

        //Listenerにイベントを登録
        $this->getServer()->getPluginManager()->registerEvents(new Event($this),$this);

        //scheduleRepeatingTaskにTipにステータスを表示させる為に登録
        $this->getScheduler()->scheduleRepeatingTask(new Status($api),20);
    }
}
