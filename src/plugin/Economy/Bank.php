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
        return $this->getAccountConfig()->getNested($name.".DepositBalance");
    }

    //預金残高追加
    public function addDepositBalance($name,$money){
        $depositBalance = $this->getDepositBalance($name);
        $depositBalance += $money;
        $this->setDepositBalance($name,$depositBalance);
        $this->saveAccountConfig();
    }

    //預金残高出金
    public function reduceDepositBalance($name,$money){
        $depositBalance = $this->getDepositBalance($name);
        $depositBalance -= $money;
        $this->setDepositBalance($name,$depositBalance);
        $this->saveAccountConfig();
    }

    //銀行口座が開設されているか確認
    public function checkAccount($name){
        if($this->getAccountConfig()->exists($name)){
            return true;
        }else{
            return false;
        }
    }

    //ローンの残金を取得
    public function getLoan($name){
        return $this->getAccountConfig()->getNested($name.".Loan");
    }

    //ローンを申請
    public function applicationLoan($name,$money){
        $this->getLoanConfig()->set($name,$money);
        $this->saveLoanConfig();
    }

    /**
     * ローンを許可した段階で銀行口座に
     * ローンで申し込んだ分のお金を振り込む
     * また銀行資金からその分のお金を引く
     */

    //ローンを許可して追加
    public function addLoan($name,$money){
        $this->getLoanConfig()->remove($name);
        $this->saveLoanConfig();
        $this->getAccountConfig()->setNested($name.".Loan",$money);
        $this->saveAccountConfig();
    }

    //ローンの申請があるか確認
    public function checkApplicationLoan($name){
        if($this->getLoanConfig()->exists($name)){
           return true;
        }else{
            return false;
        }
    }

    //ローンの承認を却下
    public function rejecteLoan($name){
        $this->getLoanConfig()->remove($name);
        $this->saveLoanConfig();
    }

    //ローンを返済
    public function repaymentLoan($name,$money){
        $loan = $this->getAccountConfig()->getNested($name.".Loan");
        $loan -= $money;
        $this->getAccountConfig()->setNested($name.".Loan",$loan);
        $this->saveAccountConfig();
    }

    //ローンがあるか確認
    public function checkLoan($name){
        if($this->getAccountConfig()->exists($name.".Loan")){
            return true;
        }else{
            return false;
        }
    }

    //現在承認まちのローンを取得
    public function getApplicationLoan(){
        return $loan = $this->getLoanConfig()->getAll();
    }

    

    //口座開設
    public function accountOpening($name){
        $this->getAccountConfig()->set($name,array(
            "DepositBalance" => 0
            ));
        $this->saveAccountConfig();
    }

    /**
     * ローンの資金の出所は銀行の資金から出す
     * もしローンの回収がきちんと出来てなければ銀行は新規ローンの追加が不可能
     * の為、ローン返済されない場合は口座停止などのペナルティを追加するべき
     */

    //銀行資金を確認
    public function getBankMoney(){
        return $this->getBankConfig()->get("money");
    }

    //銀行資金を追加
    public function addBankMoney($money){
        $now = $this->getBankMoney();
        $now += $money;
        $this->setBankMoney($now);
        $this->getBankConfig()->saveBankConfig();
    }

    //銀行資金を減らす
    public function reduceBankMoney($money){
        $now = $this->getBankMoney();
        $now += $money;
        $this->setBankMoney($now);
        $this->getBankMoney()->saveBankConfig();
    }





    

    private function setBankMoney($money){
        $this->getBankConfig()->set("money",$money);
    }

    //configに書き込む用
    private function setDepositBalance($name,$depositBalance){
        $this->getConfig()->setNested($name.".DepositBalance",$depositBalance);
    }
    
    private function getAccountConfig(){
        return ConfigBase::getFor(ConfigList::BANK_ACCOUNT);
    }

    private function getBankConfig(){
        return ConfigBase::getFor(ConfigList::BANK);
    }

    private function getLoanConfig(){
        return ConfigBase::getFor(ConfigList::BANK_LOAN);
    }
    
    private function saveAccountConfig(){
        $this->getAccountConfig()->save();
    }

    private function saveBankConfig(){
        $this->getBankConfig()->save();
    }

    private function saveLoanConfig(){
        $this->getLoanConfig()->save();
    }
}