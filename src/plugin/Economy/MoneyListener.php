<?php

declare(strict_types=1);

namespace plugin\Economy;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Config\PlayerConfigBase;

class MoneyListener
{
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
		}else{
			$max += self::getMoney();
		}
		self::setMoney($max);
		self::save();
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