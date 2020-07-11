<?php

namespace plugin\NPC;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\item\Item;

use pocketmine\math\Vector3;

use pocketmine\utils\UUID;

use pocketmine\network\mcpe\protocol\AddPlayerPacket;

class FloatText{
	public function __construct($config){
		$this->config = $config;
	}

	public function FloatText(Player $player, $text, $eid, $y=0){
		echo "float";
		$move = 0;
		$plusY = 0;
		$eid = $eid;
		$pk = new AddPlayerPacket();
		$pk->entityRuntimeId = $eid;
		$pk->username = $text;
		$pk->uuid = UUID::fromRandom();
		$pk->position = new Vector3($this->config->get("x"), $this->config->get("y") + $y, $this->config->get("z"));
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
		// データが保管されてあるか確認
		if(isset($eid[$name])){
			self::FloatText($player, "§l§eName : ".$name."", $eid[$name][0], 3);
			self::FloatText($player, "職業 : NULL", $eid[$name][1], 2.7);
			self::FloatText($player, "E-Club期限 : --:--", $eid[$name][2], 2.4);
			self::FloatText($player, "銀行残高 : 0M", $eid[$name][3], 2.1);
		}
	}
}