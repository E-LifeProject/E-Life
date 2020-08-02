<?php

namespace plugin\NPC;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\entity\Entity;
use pocketmine\entity\Skin;

use pocketmine\item\Item;

use pocketmine\math\Vector3;

use pocketmine\utils\UUID;

use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;

class governmentNPC{

	public function __construct($skin){
		$this->skin = $skin;
	}

	public function showNPC(Player $player, $eid, $yaw, $headYaw){
		$npcname = "政府管理者";
		$pk = new AddPlayerPacket();
		$pk->entityRuntimeId = $eid;
		$pk->uuid = UUID::fromRandom();
		$pk->username = $npcname;
		$pk->position = new Vector3(222, 8, 265); 
	   	$pk->yaw = $yaw;
	   	$pk->headYaw = $headYaw;
	 	$pk->pitch = 0;
	    $pk->item = Item::get(266, 0, 1);

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

		/**$pk2 = new MobEquipmentPacket();
		$pk2->entityRuntimeId = $eid;
		$pk2->item = Item::get(266, 0, 1);
		$pk2->inventorySlot = 0;
		$pk2->hotbarSlot = 0;
		$player->dataPacket($pk2);//Item */
    }
}