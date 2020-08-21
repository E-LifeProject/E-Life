<?php

declare(strict_types=1);

namespace plugin\Economy;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Config\PlayerConfigBase;

class MoneyListener
{
	/**
	 * 所持金の最高限度額は50万
	 * その額を超える所得があった場合には政府が保管庫にて
	 * 現金を保管する。この時、プレーヤーは期日までに現金を
	 * 保管庫から銀行口座か所持金に移行しなければ、
	 * 最初の15日以降一週間毎に3000円が徴収される
	 */

	/** @var string **/
	private $name;

	/** @var Config **/
	private $config;

	/**
	 * Company constructor.
	 * @param string $name
	 */
	public function __construct(string $name){
		$this->name = strtolower($name);
		$this->name2 = $name;
		$this->config = PlayerConfigBase::getFor($this->name);
	}

	/** @return string **/
	public function getName(){
		return $this->name;
	}

	/** @return Config **/
	public function getConfig(){
		return $this->config;
	}

	public function getMoney(): int {
		return $this->config->get("money");
	}

	public function setMoney(int $money){
		$this->config->set("money", $money);
		self::save();
	}

	public function addMoney(int $money){
		$total = self::getMoney() + $money;

		if($total > 500000){
			$max = 500000;
			$over = $total - $max;
			$this->addMoneyStorage($over);
			self::setMoney($max);
			self::save();
		}else{
			self::setMoney($total);
			self::save();
		}
	}

	public function reduceMoney(int $money){
		$total = self::getMoney();
		$total -= $money;
		self::setMoney($total);
		self::save();
	}



	//保管金があるかどうか確認
	public function checkMoneyStorage(){
		if($this->getStorageConfig()->getNested($this->name2.".Money") > 0){
			return true;
		}else{
			return false;
		}
	}

	//保管金を確認
	public function getMoneyStorage(){
		return $this->getStorageConfig()->getNested($this->name2.".Money");
	}

	//保管金受け取り期日を確認
	public function getMoneyStorageDate(){
		return $this->getStorageConfig()->getNested($this->name2.".Date");
	}

	//保管金を追加
	public function addMoneyStorage($money){

		if($this->getStorageConfig()->exists($this->name2)){
			$now = $this->getStorageConfig()->getNested($this->name2.".Money");
		}else{
			$this->getStorageConfig()->setNested($this->name2.".Money",0);
			$this->getStorageConfig()->setNested($this->name2.".Date",0);
			$now = 0;
		}
		$total = $now + $money;
		$this->getStorageConfig()->setNested($this->name2.".Money",$total);
		if($this->getStorageConfig()->getNested($this->name2.".Date") == 0){
			$this->getStorageConfig()->setNested($this->name2.".Date",date("Y/m/d",strtotime("15 day")));
		}
		$this->getStorageConfig()->save();
	}

	//保管金を減らす
	public function reduceMoneyStorage($money){
		$now = $this->getStorageConfig()->getNested($this->name2.".Money");
		$total = $now - $money;
		$this->getStorageConfig()->setNested($this->name2.".Money",$total);
		$this->getStorageConfig()->save();
	}



	private function getStorageConfig(){
		return ConfigBase::getFor(ConfigList::CASH_STORAGE);
	}

	public function save(){
		$this->config->save();
	}
}