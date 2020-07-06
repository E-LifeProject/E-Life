<?php

namespace StatusNPC;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\InteractPacket;

use StatusNPC\NPC\NPC;
use StatusNPC\NPC\FloatText;

class EventListener implements Listener{
	public function __construct(Main $main){
		$this->main = $main;
		$this->npc = new NPC($this->main->config);
		$this->text = new FloatText($this->main->config);
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$this->npc->showNPC($player, $this->main->npc, 155, 155);
		$this->main->eid[$name][0] = mt_rand(10000000,100000000000);
		$this->main->eid[$name][1] = mt_rand(10000000,100000000000);
		$this->main->eid[$name][2] = mt_rand(10000000,100000000000);
		$this->main->eid[$name][3] = mt_rand(10000000,100000000000);
		self::text($player);

		$datas = $this->npc->getSkinData($player);
	}

	public function onQuit(PlayerQuitEvent $event){
		unset($this->main->eid[$event->getPlayer()->getName()]);
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		if($pk instanceof InteractPacket){
			$player = $event->getPlayer();
			$eid = $pk->target;
			if($eid === null){
				return false;
			}

			if($eid === $this->main->npc){
				self::text($player);
			}

		}
	}

	public function text($player){
		$name = $player->getName();
		$bank = $this->main->bank->getMoney($name);
		$this->text->FloatText($player, "§l§eName : ".$name."", 3, $this->main->eid[$name][0]);
		$this->text->FloatText($player, "職業 : NULL", 2.7, $this->main->eid[$name][1]);
		$this->text->FloatText($player, "E-Club期限 : --:--", 2.4, $this->main->eid[$name][2]);
		$this->text->FloatText($player, "銀行残高 : ".$bank, 2.1, $this->main->eid[$name][3]);
	}
}