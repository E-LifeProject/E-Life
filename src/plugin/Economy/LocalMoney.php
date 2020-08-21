<?php

namespace plugin\Economy;

#Basic
use pocketmine\utils\Config;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class LocalMoney{

    static $instance;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Bank();
        }
        return self::$instance;
    }

    public function getLocalMoney($cityName){
        return $this->getConfig()->getNested($cityName.".money");
    }

    public function addLocalMoney($cityName,$money){
        $cityMoney = $this->getLocalMoney($cityName);
        $cityMoney += $money;
        $this->setLocalMoney($cityName,$cityMoney);
    }

    public function reduceLocalMoney($cityName,$money){
        $cityMoney = $this->getLocalMoney($cityName);
        $cityMoney -= $money;
        $this->setLocalMoney($cityName,$cityMoney);
    }

    private function setLocalMoney($cityName,$money){
        $this->getConfig()->setNested($cityName.".money",$money);
    }

    private function getConfig(){
       return ConfigBase::getFor(ConfigList::LOCAL);
    }
}