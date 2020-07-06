<?php

namespace bank;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use bank\DataBase\CreateAccount;

class Main extends PluginBase{

	public function onEnable(){
    	$this->saveDefaultConfig();
		$this->setting = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->skin = new Config($this->getDataFolder() . "skinData.yml", Config::YAML);

        $this->npc = mt_rand(1, 999999999);

		$folder = $this->getDataFolder().'/users/';
		if(!file_exists($folder)) mkdir($folder);

		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if($this->api == null){
            $this->getLogger()->error("EconomyAPIを読み込むことが出来ませんでした");
            $this->getServer()->shutdown();
        }else{
            $this->getLogger()->info("EconomyAPIを読み込みました");
        }

        $this->e_life = $this->getServer()->getPluginManager()->getPlugin("E-Life");
        if($this->e_life == null){
            $this->getLogger()->error("E-Lifeを読み込むことが出来ませんでした");
            $this->getServer()->shutdown();
        }else{
            $this->getLogger()->info("E-Lifeを読み込みました");
        }

        self::setConfig();
	}

    public function checkFee(){
        $now_data = intval(date("H"));
        foreach (array(21, 22, 23, 24, 01, 02, 03, 04, 05, 06) as $date) {
            if($date === $now_data){
                return true;
            }
        }
        return false;
    }

    public function checkMember($name){
        if($this->e_life->club->exists($name)){
            return false;
        } else {
            return true;
        }
    }

    public function getMoney($name){
        if(isset($this->config[$name])){
            return $this->config[$name]->get("money");
        } else {
            return 0;
        }
    }

    public function setMoney($name, $money){
        $this->config[$name]->set("money", $money);
    }

    public function addMoney($name, $money){
        $player_money = $this->getMoney($name);
        $this->setMoney($name, $player_money + $money);
    }

    public function canReduceMoney($name, $money){
        $player_money = $this->getMoney($name);
        return ($player_money >= $money) ? true : false;
    }

    public function reduceMoney($name, $money){
        $money = $this->getMoney($name) - $money;
        $this->setMoney($name, $money);        
    }

    public function getPassword($name){
        return $this->config[$name]->get("password");
    }

    public function setPassword($name, $pass){
        $this->config[$name]->set("password", $pass);
    }

    public function getDebt($name){
        return $this->config[$name]->get("debt");
    }

    public function setDebt($name, $money){
        $this->config[$name]->set("debt", $money);
    }

    public function addDebt($name, $money){
        $money += self::getDebt($name);
        self::setDebt($name, $money);
    }

    public function reduceDebt($name, $money){
        $money = self::getDebt($name) - $money;
        self::setDebt($name, $money);        
    }

    public function getCardMoney($item){
        return $item->getNameTagEntry("money");
    }

    public function getOfflinePlayers(){
        $files = scandir($this->getServer()->getDataPath()."players/");
        $players = [];
        foreach($files as $file) {
            $players[] = rtrim($file, ".dat");
        }
        return $players;
    }

    public function setConfig(){
        $players = self::getOfflinePlayers();
        $account = new CreateAccount($this);
        foreach ($players as $player) {
            if(mb_strlen($player) === 0){
                continue;
            } else {
                $folder = $account->getFolder($player);
                $this->config[$player] = new Config($folder, Config::JSON, [
                    'passbook'  => false,
                    'password'  => 0,
                    'money'     => 0,
                    'debt'      => 0
                ]);
            }
        }
    }
}