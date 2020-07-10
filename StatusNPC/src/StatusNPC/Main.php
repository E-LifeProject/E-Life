<?php

namespace StatusNPC;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

class Main extends PluginBase{
	public function onEnable(){
    	$this->saveDefaultConfig();
		
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);		

		$this->npc = mt_rand(1, 999999999);

		if($this->getServer()->getPluginManager()->getPlugin("Bank") != null){
      	  $this->bank = $this->getServer()->getPluginManager()->getPlugin("Bank");
      	  $this->getLogger()->info("Bankを検出しました");
      	}else{
      	  $this->getLogger()->warning("Bankが見つかりませんでした");
      	  $this->getServer()->getPluginManager()->disablePlugin($this);
    	}

   		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
}