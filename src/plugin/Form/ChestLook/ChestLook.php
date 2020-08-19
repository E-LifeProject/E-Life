<?php


namespace plugin\Form\ChestLook;

use plugin\Config\Data\ChestLookData;
use pocketmine\Player;
use pocketmine\form\Form;

class ChestLook implements Form
{

    public function __construct(int $x,int $y, int $z, string $world){

         $this->x = $x;
         $this->y = $y;
         $this->z = $z;
         $this->world = $world;


    }

	public function handleResponse(Player $player, $data): void
	{

		if ($data === null) {
			return;
		}
		switch ($data) {
			case "0":
			ChestLookData::get()->addchestlook($player->getName(), $this->x, $this->y, $this->z, $this->world);
			ChestLookData::get()->save();
		}
	}

	public function jsonSerialize()
	{
		return [
			"type" => "form",
			"title" => "チェストロック",
			"content" => "チェストロック",
			"buttons" => [
				[
					"text" => "チェストロックする",
				],
				[
					"text" => "チェストロックしない"
				],
			],
		];
	}
}