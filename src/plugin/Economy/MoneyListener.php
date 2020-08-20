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
	 * 1日あたり300円の現金保管料が徴収される
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
			$now = $this->getMoneyStorage();
			$this->addMoneyStorage($over+$now);
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

	public function getMoneyStorage(){
		return $this->getStorageConfig()->getNested($this->name.".Money");
	}

	public function addMoneyStorage($money){
		$this->getStorageConfig()->setNested($this->name.".Money",$money);
		$this->getStorageConfig()->save();
	}

	private function getStorageConfig(){
		return ConfigBase::getFor(ConfigList::CASH_STORAGE);
	}

	public function save(){
		$this->config->save();
	}
}