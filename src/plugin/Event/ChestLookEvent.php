<?php


namespace plugin\Event;

use pocketmine\block\BlockIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use plugin\Form\ChestLook\ChestLookForm;
use plugin\Config\Data\ChestLookData;
use plugin\Form\ChestLook\ChestLook;

class ChestLookEvent implements Listener
{
	public function onplace(BlockPlaceEvent $event)
	{
		if ($event->getBlock()->getId() == BlockIds::CHEST) {
			$x = $event->getBlock()->getX();
			$y = $event->getBlock()->getY();
			$z = $event->getBlock()->getZ();
			$world = $event->getBlock()->getLevel()->getName();
			$event->getPlayer()->sendForm(new ChestLook($x,$y,$z,$world));
			ChestLookData::get()->save();
		}
	}

	public function onBreak(BlockBreakEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock()->getId();
		$name = $player->getName();
		$world = $event->getBlock()->getLevel()->getName();
		$x = $event->getBlock()->getX();
		$y = $event->getBlock()->getY();
		$z = $event->getBlock()->getZ();
		if ($block == BlockIds::CHEST) {
			if ($player->isOp() == true || ChestLookData::get()->cheakchestlook($name, $x, $y, $z, $world) == true) {
				ChestLookData::get()->removechestlook($name, $x, $y, $z, $world);
				ChestLookData::get()->save();
				$player->sendMessage("§aチェストロックを解除しました");
			} else {
				$event->setCancelled(true);
				$player->sendMessage("§aこのチェストは破壊できません");
			}
		}
	}

public function onChest(PlayerInteractEvent $event)
{
	if ($event->getBlock()->getId() == BlockIds::CHEST) {
				$player = $event->getPlayer();
		$name = $player->getName();
		$x = $event->getBlock()->getX();
		$y = $event->getBlock()->getY();
		$z = $event->getBlock()->getZ();
		$world = $event->getBlock()->getLevel()->getName();

		if(ChestLookData::get()->getCommandStatus($event->getPlayer()->getName()) == 1){
			if(ChestLookData::get()->cheakchestlook($name, $x, $y, $z, $world) == false){
				ChestLookData::get()->addchestlook($name,$x,$y,$z,$world);
				ChestLookData::get()->save();
				ChestLookData::get()->setCommandStatus(0,$name);
				$player->sendMessage("§aチェストをロックしました");
			}
			if(ChestLookData::get()->getCommandStatus($player->getName()) == 2){
				if($player->isOp() == true || ChestLookData::get()->cheakchestlook($name, $x, $y, $z, $world) == true){
					ChestLookData::get()->removechestlook($name,$x,$y,$z,$world);
					ChestLookData::get()->save();
					$player->sendMessage("§aチェストロックを解除しました");
					ChestLookData::get()->setCommandStatus(0,$name);
				}
			}
		}

		$id = ChestLookData::get()->getid($name,$x,$y,$z,$world);
		if ($player->isOp() == false) {
			if (ChestLookData::get()->cheakchestlook($name, $x, $y, $z, $world) == false || ChestLookData::get()->cheackinvites($id,$name) == false) {
				$event->setCancelled();
				$player->sendMessage("§aチェストにロックかかってます");
			}
		}
	}
}
}