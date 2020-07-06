<?php

namespace bank;

use pocketmine\event\Listener;

use pocketmine\Server;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use pocketmine\math\Vector3;

use pocketmine\utils\Config;

use bank\Form\MainMenu;
use bank\Form\Money\Debt\black\DebtCheck;
use bank\Func\FloatText;
use bank\DataBase\CreateAccount;
use bank\NPC\NPC;
use bank\NPC\Particle;

class EventListener implements Listener{
	public function __construct(Main $main){
		$this->main = $main;
		$this->setting = $main->setting;
		$this->npc = new NPC($main->skin, $this->setting);
		$this->particle = new Particle($this->main);
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$float = new FloatText($this->setting);
		$float->FloatText($player);

		$account = new CreateAccount($this->main);
		$folder = $account->getFolder($name);
		$this->main->config[$name] = new Config($folder, Config::JSON, [
			'passbook'     => false,
			'password'       => 0,
			'money'      => 0,
			'debt'      => 0
		]);
		$this->npc->showNPC($player, $this->main->npc, 60, 30);
	}

	public function PlayerQuitEvent(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		if($player->loggedIn){
			$name = $player->getName();
			$this->main->config[$name]->save();
			unset($this->main->config[$name]);
		}
	}

	public function onTap(PlayerInteractEvent $event){
		$action = $event->getAction();
		if($action === PlayerInteractEvent::LEFT_CLICK_BLOCK || $action === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
			$block = $event->getBlock();
			$level = $block->getLevel();

			$x = $this->setting->get("bank-x");
			$y = $this->setting->get("bank-y");
			$z = $this->setting->get("bank-z");
			$pos = new Vector3($x, $y, $z);
			$target_block = $level->getBlock($pos);

			if($target_block === $block){
				$player = $event->getPlayer();
				$player->sendForm(new MainMenu($this->main));
			}
		}
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		if($pk instanceof InventoryTransactionPacket){
			$player = $event->getPlayer();
			$eid = $pk->trData->entityRuntimeId ?? null;;
			if($eid === null){
				return false;
			}
			if($eid === $this->main->npc){
				$player->sendForm(new DebtCheck($this->main));
				$pk = new LevelSoundEventPacket;
				$pk->sound = LevelSoundEventPacket::SOUND_ITEM_TRIDENT_THUNDER;
				$pk->position = new Vector3($this->setting->get("debt-x"), $this->setting->get("debt-y"), $this->setting->get("debt-z"));
				#$pk->addPacket($player);
				Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pk);
				# $this->particle->createParticle($player, 1);
			}
		}
	}
}