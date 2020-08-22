<?php


namespace plugin\Form\ChestLook;

use plugin\Config\Data\ChestLookData;
use pocketmine\form\Form;
use pocketmine\Player;

class ChestLookListShowForm implements Form
{
public function __construct(int $id){
		$this->id = $id;

	}
		public function handleResponse(Player $player, $data): void
		{
			if ($data === null) {
				return;
			}

		}
		public function jsonSerialize()
	{

		$cofig = ChestLookData::get()->getconfig($this->id);
		$id = $this->id;
		$name = $cofig["所持者"];
		$x = $cofig["chestx"];
		$y = $cofig["chesty"];
		$z = $cofig["chestz"];
		$world = $cofig["world"];
		$invites = $cofig["invites"];
		if(count($invites) == 0) {
			$invites = "いません";
		}else{
			$invites = implode(",", $invites);
		}



		return [
			"type" => "form",
			"title" => "チェストロック",
			"content" => "ID:$id\n所持者:$name\nX:$x\nY:$y\nZ:$z\nWorld:$world\n共有者:$invites",
			"buttons" => [
				[
					"text" => "閉じる"
				],
			],
		];
	}

}