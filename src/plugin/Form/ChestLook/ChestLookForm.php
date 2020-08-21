<?php

namespace plugin\Form\ChestLook;


use pocketmine\form\Form;
use pocketmine\Player;
use plugin\Config\Data\ChestLookData;

class ChestLookForm implements Form
{

	public function handleResponse(Player $player, $data): void
	{

		if ($data === null) {
			return;
		}

		switch ($data) {
			case "0":
				if (count(ChestLookData::get()->Examineplayerland($player)) == 0) {
					$player->sendMessage("§a貴方が所有してる土地はありません");
					break;
				} else {
					$player->sendForm(new ChestLockInitesForm($player));
					break;
				}

			case "1":
				ChestLookData::get()->setCommandStatus(1,$player->getName());
				$player->sendMessage("§aロックしたいチェストを触ってください");
				break;
			case "2":
				ChestLookData::get()->setCommandStatus(2,$player->getName());
				$player->sendMessage("§aロックを解除したいチェストを触ってください");
				break;
			case "3":
				$player->sendForm(new ChestLookList($player));
				break;

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
					"text" => "共有する",
				],
				[
					"text" => "ロックする,"
				],
				[
					"text" => "ロックを解除する"
				],
				[
					"text" => "リスト"
				],
			],
		];
	}
}