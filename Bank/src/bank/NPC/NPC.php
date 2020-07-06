<?php

namespace bank\NPC;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\entity\Entity;
use pocketmine\entity\Skin;

use pocketmine\item\Item;

use pocketmine\math\Vector3;

use pocketmine\utils\UUID;

use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;

class NPC{
	public function __construct($skin, $setting){
		$this->skin = $skin;
		$this->setting = $setting;
	}

	public function showNPC(Player $player, $eid, $yaw, $headYaw){
		$npcname = $this->setting->get("name");
		$pk = new AddPlayerPacket();
		$pk->entityRuntimeId = $eid;
		$pk->uuid = UUID::fromRandom();
		$pk->username = $npcname;
		$pk->position = new Vector3($this->setting->get("debt-x"), $this->setting->get("debt-y"), $this->setting->get("debt-z"));
	   	$pk->yaw = $yaw;
	   	$pk->headYaw = $headYaw;
	 	$pk->pitch = 0;
	    $pk->item = Item::get($this->setting->get("id"), $this->setting->get("meta"), 1);

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

		$geometryJsonEncoded = base64_decode($this->skin->get("geometrydata"));
		if($geometryJsonEncoded !== ""){
			$geometryJsonEncoded = \json_encode(\json_decode($geometryJsonEncoded));
		}
		$skin = new Skin(base64_decode($this->skin->get("skinid")), base64_decode($this->skin->get("skindata")), base64_decode($this->skin->get("capedata")), base64_decode($this->skin->get("geometryname")), $geometryJsonEncoded);
		$xbox = mt_rand(100000, 1000000000);
		Server::getInstance()->updatePlayerListData($pk->uuid, $pk->entityRuntimeId, $npcname, $skin, $xbox, Server::getInstance()->getOnlinePlayers());
		$player->dataPacket($pk);

		$pk2 = new MobEquipmentPacket();
		$pk2->entityRuntimeId = $eid;
		$pk2->item = Item::get($this->setting->get("id"), $this->setting->get("meta"), 1);
		$pk2->inventorySlot = 0;
		$pk2->hotbarSlot = 0;
		$player->dataPacket($pk2);//Item
	}
}