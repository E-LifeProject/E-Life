<?php

namespace plugin\Economy;

#Basic
use pocketmine\utils\Config;
use pocktemine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class Bank{

    /**
     * ローンは審査が必要で、未審査のものはBank_Loanで保存される
     * 審査はOPが管理フォームから行い、その時の銀行の貸し出し状況などを見て総合的に判断する
     * 審査が通るとBank_AccountのLoan項目で保存される
     */

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

    //預金残高を減らす
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

    //口座開設
    public function accountOpening($name){
        $this->getAccountConfig()->set($name,array(
            "DepositBalance" => 0,
            "Loan"=>[
                "Money"=>0,
                "Date"=>0
            ]
            ));
        $this->saveAccountConfig();
    }



    //ローン関係の関数==========


    //ローンを申請
    public function applicationLoan($name,$money){
        $this->getLoanConfig()->set($name,$money);
        $this->saveLoanConfig();
    }

    //現在承認待ちのローンを全取得
    public function getApplicationLoan(){
        return $loan = $this->getLoanConfig()->getAll();
    }

    //ローンの申請があるか確認
    public function checkApplicationLoan($name){
        if($this->getLoanConfig()->exists($name)){
           return true;
        }else{
            return false;
        }
    }

    /**
     * ローンを許可した段階で銀行口座に
     * ローンで申し込んだ分のお金を振り込む
     * また銀行資金からその分のお金を引く
     */


    //ローンの申請を許可して追加
    public function addLoan($name,$money){
        $this->getLoanConfig()->remove($name);
        $this->saveLoanConfig();
        $this->getAccountConfig()->setNested($name.".Loan.Money",$money);
        $this->getAccountConfig()->setNested($name.".Loan.Date",date("Y/m/d",strtotime("20 day")));
        $this->saveAccountConfig();
        $this->addDepositBalance($name,$money);
        $this->reduceBankMoney($money);
    }

    //ローンの申請を取り下げる
    public function rejecteLoan($name){
        $this->getLoanConfig()->remove($name);
        $this->saveLoanConfig();
    }

    //ローンの残金を取得
    public function getLoan($name){
        return $this->getAccountConfig()->getNested($name.".Loan.Money");
    }


    //ローンを返済
    public function repaymentLoan($name,$money){
        $loan = $this->getAccountConfig()->getNested($name.".Loan.Money");
        $loan -= $money;
        $this->getAccountConfig()->setNested($name.".Loan.Money",$loan);
        $this->saveAccountConfig();
        $this->addBankMoney($money);
    }

    //ローンがあるか確認
    public function checkLoan($name){
        $loan = $this->getAccountConfig()->getNested($name.".Loan.Money");
        if($loan > 0){
            return true;
        }else{
            return false;
        }
    }

     //返済期日を取得する
     public function getLoanDate($name){
        return $this->getAccountConfig()->getNested($name.".Loan.Date");
    }

    //支払い期日を過ぎてペナルティを付与する
    public function addPenalty($name){
        $this->getPenaltyConfig()->set($name);
        $this->savePenaltyConfig();
    }



    //銀行資金などの銀行内部関連==========

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
        $this->saveBankConfig();
    }

    //銀行資金を減らす
    public function reduceBankMoney($money){
        $now = $this->getBankMoney();
        $now -= $money;
        $this->setBankMoney($now);
        $this->saveBankConfig();
    }





    

    private function setBankMoney($money){
        $this->getBankConfig()->set("money",$money);
    }

    private function setDepositBalance($name,$depositBalance){
        $this->getAccountConfig()->setNested($name.".DepositBalance",$depositBalance);
    }

    private function getPenaltyConfig(){
        return ConfigBase::getFor(ConfigList::PENALTY);
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

    private function savePenaltyConfig(){
        $this->getPenaltyConfig()->save();
    }
}