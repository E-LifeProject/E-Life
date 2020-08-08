<?php

namespace plugin\Economy\Government;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

/**
 * 政府の預金残高（国庫）に関するクラス
 */


class GovernmentMoney{
    

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new GovernmentMoney();
        }
        return self::$instance;
    }



    public function getMoney(){
        $this->getConfig()->get("money");
    }

    public function addMoney($money){
        $totalMoney = $this->getMoney()+$money;
        $this->getConfig()->set("money",$totalMoney);
        $this->save();
    }

    public function reduceMoney($money){
        $totalMoney = $this->getMoney()-$money;
        $this->setMoney($totalMoney);
        $this->save();
    }



    private function save(){
        $this->getConfig()->save();
    }

    private function setMoney($money){
        $this->getConfig()->set("money",$money);
    }

    private function getConfig(){
        $config = ConfigBase::getFor(ConfigList::GORVERNMENTMONEY);
        return $config;
    }

}

?>