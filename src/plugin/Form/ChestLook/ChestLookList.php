<?php


namespace plugin\Form\ChestLook;

use pocketmine\form\Form;
use pocketmine\Player;
use plugin\Config\Data\ChestLookData;


class ChestLookList implements Form
{
	public function __construct(Player $player){
		$this->player = $player;

	}
		public function handleResponse(Player $player, $data): void
		{
			if ($data === null) {
				return;
			}

		}
		public function jsonSerialize()
	{

		$formid = ChestLookData::get()->Examineplayerlandb($this->player);


		return [
			"type" => "form",
			"title" => "チェストロック",
			"content" => "チェストロック",
			"buttons" => $formid
		];
	}

}