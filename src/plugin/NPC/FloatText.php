<?php

namespace plugin\NPC;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\item\Item;

use pocketmine\math\Vector3;

use pocketmine\utils\UUID;

use pocketmine\network\mcpe\protocol\AddPlayerPacket;

use plugin\Economy\Bank;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Utils\Reliability;

class FloatText{

	public function FloatText(Player $player, $text, $eid, $y=0){
		$move = 0;
		$plusY = 0;
		$eid = $eid;
		$pk = new AddPlayerPacket();
		$pk->entityRuntimeId = $eid;
		$pk->username = $text;
		$pk->uuid = UUID::fromRandom();
		$pk->position = new Vector3(234.8785,6.5+$y,233.6184);
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->item = Item::get(0);
		@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
		@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
		@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
		@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
		$pk->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $text],
			Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
 			Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0],//大きさ
		];
		$player->dataPacket($pk);
	}

	//StatusNPC用
	/** @return array */
	public function getStatusNpcEid($player){
		$name = $player->getName();
		$eid[$name][0] = mt_rand(10000000,100000000000);
		$eid[$name][1] = mt_rand(10000000,100000000000);
		$eid[$name][2] = mt_rand(10000000,100000000000);
		$eid[$name][3] = mt_rand(10000000,100000000000);
		return $eid;
	}

	//StatusNPC用
	public function showText($player, $eid){
		$name = $player->getName();
		$bank = Bank::getInstance();
		$club = ConfigBase::getFor(ConfigList::CLUB);
		$reliability = new Reliability($name);
		$reliability->reliabilityCalculation();
		$point = $reliability->getReliability();


		if($club->exists($name)){
			$clubData = $club->get($name);
		}else{
			$clubData = "--:--";
		}

		if($bank->checkLoan($name)){
			$loan = $bank->getLoanDate($name);
		}else{
			$loan = "--:--";
		}

		if($bank->checkAccount($name)){
			$money = $bank->getDepositBalance($name);
		}else{
			$money = 0;
		}

		// データが保管されてあるか確認
		if(isset($eid[$name])){
			self::FloatText($player, "§l§e名前 : ".$name."", $eid[$name][0], 3);
			self::FloatText($player, "返済期日 : ".$loan, $eid[$name][1], 2.7);
			self::FloatText($player, "E-Club期限 : ".$clubData, $eid[$name][2], 2.4);
			self::FloatText($player, "信用度 : ".$reliability->getReliabilityColour()."■§f".$point."/100", $eid[$name][3], 2.1);
		}
	}
}