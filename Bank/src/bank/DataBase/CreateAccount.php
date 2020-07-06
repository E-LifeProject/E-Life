<?php

namespace bank\DataBase;

use bank\Main;
use bank\BankItem;

class CreateAccount{
	public function __construct(Main $main){
		$this->main = $main;
		$this->item = new BankItem($this->main);
	}

	public function getFolder($name){
		$sub = substr($name, 0, 1);
		$upper = strtoupper($sub);
		$folder = $this->main->getDataFolder().'/users/'.$upper.'/';
		if(!file_exists($folder)) mkdir($folder);
		$lower = strtolower($name);
		
		return $folder .= $lower.'.json';
	}

	public function enablePassbook($player, $pass, $index=0){
		$name = $player->getName();
		$passbook = $this->main->config[$name]->get("passbook");

		$item = $this->item->createPassCard($name, $pass);
		if($index === 0){
			if(!$passbook){
				$player->getInventory()->addItem($item);
				$this->main->config[$name]->set("passbook", true);
				$this->main->config[$name]->set("password", $pass);
				$player->sendMessage("§l§a>通帳を発行しました<");
				$this->main->api->getInstance()->reduceMoney($player, $this->main->setting->get("passbook-fee"));
			} else {
				$player->sendMessage("§l§c>既に通帳を作成しています<");
			}
		}elseif($index === 1){
			$player->getInventory()->addItem($item);
			$this->main->config[$name]->set("password", $pass);
			$player->sendMessage("§l§a>通帳を再発行しました<");
			$this->main->api->getInstance()->reduceMoney($player, $this->main->setting->get("reissue-passbook-fee"));	
		}
	}
}