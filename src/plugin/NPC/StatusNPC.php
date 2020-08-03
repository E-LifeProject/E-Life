<?php

namespace plugin\NPC;


#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\utils\UUID;

#Entity
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;

#Packet
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;

class StatusNPC{

	public function __construct($config){
		$this->config = $config;
	}

	public function showNPC(Player $player, $eid, $yaw, $headYaw){
		$npcname = "";
		$skinData = self::getSkinData($player);
		$pk = new AddPlayerPacket();
		$pk->entityRuntimeId = $eid;
		$pk->uuid = UUID::fromRandom();
		$pk->username = $npcname;
		$pk->position = new Vector3(235.9068,8,232.4803);
	   	$pk->yaw = $yaw;
	   	$pk->headYaw = $headYaw;
	 	$pk->pitch = 0;
	    $pk->item = Item::get($this->config->get("id"), $this->config->get("meta"), 1);

		@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
		@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
		@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
		@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
		      	$pk->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $npcname],
			Entity::DATA_FLAG_NO_AI => [Entity::DATA_TYPE_BYTE, 1],
		  	Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
			Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 1],//大きさ
			];

		$geometryJsonEncoded = base64_decode($skinData["geometrydata"]);
		if($geometryJsonEncoded !== ""){
			$geometryJsonEncoded = \json_encode(\json_decode($geometryJsonEncoded));
		}
		$skin = new Skin(base64_decode($skinData["skinid"]), base64_decode($skinData["skindata"]), base64_decode($skinData["capedata"]), base64_decode($skinData["geometryname"]), $geometryJsonEncoded);
		$xbox = mt_rand(100000, 1000000000);
		Server::getInstance()->updatePlayerListData($pk->uuid, $pk->entityRuntimeId, $npcname, $skin, $xbox, Server::getInstance()->getOnlinePlayers());
		$player->dataPacket($pk);

		$pk2 = new MobEquipmentPacket();
		$pk2->entityRuntimeId = $eid;
		$pk2->item = Item::get($this->config->get("id"), $this->config->get("meta"), 1);
		$pk2->inventorySlot = 0;
		$pk2->hotbarSlot = 0;
		$player->dataPacket($pk2);//Item
	}

	public function getSkinData($player){
		$skin = $player->getSkin();
		$skinid = base64_encode($skin->getSkinId());
		$skindata = base64_encode($skin->getSkinData());
		$capedata = base64_encode($skin->getCapeData());
		$geometryname = base64_encode($skin->getGeometryName());
		$geometrydata = base64_encode($skin->getGeometryData());
		return[
			"skinid" => $skinid,
			"skindata" => $skindata,
			"capedata" => $capedata,
			"geometryname" => $geometryname,
			"geometrydata" => $geometrydata
		];
	}

}