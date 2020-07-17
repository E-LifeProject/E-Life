<?php

declare(strict_types=1);

namespace plugin\Economy;

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
		$this->money_config = ConfigBase::getFor(ConfigList::MONEY);
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
	}

	public function addMoney(int $money){
		$money += self::getMoney();
		if($money > $this->money_config->get("max")) return;
		/* 最大所持金を超えていた時の処理は後々書く */
		self::setMoney($money);
	}

	public function reduceMoney(int $money){
		$money -= self::getMoney();
		if($money < $this->money_config->get("min")) return;
		/* 最低所持金を超えていた時の処理は後々書く */
		self::setMoney($money);
	}
}