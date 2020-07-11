<?php

namespace plugin;

#Basic
use plugin\Config\ConfigBase;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

#E-Life
use plugin\Event\Event;
use plugin\Task\Status;
use plugin\Task\Club;

#EconomyAPI
use onebone\economyapi\EconomyAPI;


class Main extends PluginBase implements Listener {

    public function onEnable(): void {

        date_default_timezone_set('asia/tokyo');

        $this->getServer()->loadLevel("nature");

        ConfigBase::init($this);
        $this->npc = mt_rand(1, 99999999999);

        /** @var EconomyAPI $economy_api */
        $economy_api = ApiLoader::load($this, "EconomyAPI");

        //Listenerにイベントを登録
        $this->getServer()->getPluginManager()->registerEvents(new Event($this),$this);

        //scheduleRepeatingTaskにTipにステータスを表示させる為に登録
        $this->getScheduler()->scheduleRepeatingTask(new Status($economy_api),20);
    }

    public function onDisable(): void {
	    ConfigBase::save();
    }
}

class ApiLoader
{
	static function load(Main $main, string $api_name): ?Plugin {
		$api = $main->getServer()->getPluginManager()->getPlugin($api_name);
		if($api === null) {
			$main->getLogger()->error($api_name."を読み込むことができませんでした。");
			$main->getServer()->shutdown();
		} else $main->getLogger()->info($api_name."を読み込みました。");

		return $api;
	}
}