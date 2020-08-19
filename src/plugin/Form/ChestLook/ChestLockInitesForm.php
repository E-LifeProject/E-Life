<?php

namespace plugin\Form\ChestLook;


use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\Server;
use plugin\Config\Data\ChestLookData;

class ChestLockInitesForm implements Form
{
	public function __construct(Player $player){
		$this->player = $player;

	}

	public function handleResponse(Player $player, $data): void
	{

		if ($data === null) {
			return;
		}

		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			$player = $player->getPlayer();
			$list[] = $player->getName();
		}
		$formid = ChestLookData::get()->Examineplayerland($this->player->getName());
		$check = ChestLookData::get()->checkinvite($formid[$data[0]], $list[$data[1]]);
		if ($check === true) {

			ChestLookData::get()->addinvite($formid[$data[0]], $list[$data[1]]);
			ChestLookData::get()->save();
			$id = $formid[$data[0]];
			$l = $list[$data[1]];
			$player->sendMessage("§a{$id}を{$l}に共有しました");
		} elseif ($check === 1) {
			$player->sendMessage("§a自分のチェストロックは自分自身では共有できません");
		} elseif ($check === 2) {
			$player->sendMessage("§aすでに共有されています");
		}
	}

	public function jsonSerialize()
	{

		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			$player = $player->getPlayer();
			$list[] = $player->getName();
		}
		$formid = ChestLookData::get()->Examineplayerland($this->player->getName());
		return [
			"type" => "custom_form",
			"title" => "チェストロック",
			"content" => [
				[
					"type" => "dropdown",
					"text" => "idを選択してね",
					"options" => $formid,
				],
				[
					"type" => "dropdown",
					"text" => "共有する人を選んでね",
					"options" => $list,
				],
			],
		];
	}
}