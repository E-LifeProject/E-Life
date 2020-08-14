<?php

namespace plugin\Economy;

#Basic
use pocketmine\utils\Config;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class Bank{

    static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Bank();
        }
        return self::$instance;
    }

    //口座開設手数料確認
    public function checkAccountOpeningFee(){
        return 1500;
    }

    //手数料確認
    public function checkFee(){
        $now_data = intval(date("H"));
        foreach(array(21, 22, 23, 24, 01, 02, 03, 04, 05, 06) as $date){
            if($date === $now_data){
                return 200;
            }
        }
        return 0;   
    }

    //預金残高
    public function getDepositBalance($name){
        return $this->getConfig()->getNested($name.".DepositBalance");
    }

    //預金残高追加
    public function addDepositBalance($name,$money){
        $depositBalance = $this->getDepositBalance($name);
        $depositBalance += $money;
        $this->setDepositBalance($name,$depositBalance);
        $this->save();
    }

    //預金残高出金
    public function reduceDepositBalance($name,$money){
        $depositBalance = $this->getDepositBalance($name);
        $depositBalance -= $money;
        $this->setDepositBalance($name,$depositBalance);
        $this->save();
    }

    //銀行口座が開設されているか確認
    public function checkAccount($name){
        if($this->getConfig()->exists($name)){
            return true;
        }else{
            return false;
        }
    }

    
    /**
     * 銀行の資本金や純資金のクラスを後ほど作る
     */

    //口座開設
    public function accountOpening($name){
        $this->getConfig()->set($name,array(
            "DepositBalance" => 0
            ));
        $this->save();
    }
    

    //configに書き込む用
    private function setDepositBalance($name,$depositBalance){
        $this->getConfig()->setNested($name.".DepositBalance",$depositBalance);
    }
    
    private function getConfig(){
        return ConfigBase::getFor(ConfigList::BANK_ACCOUNT);
    }
    
    private function save(){
        $this->getConfig()->save();
    }
}