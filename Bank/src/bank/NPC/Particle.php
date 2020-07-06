<?php

namespace bank\NPC;

use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\partical\SmokeParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;

use bank\Main;

class Particle{
	public function __construct(Main $main){
		$this->main = $main;
		$this->setting = $main->setting;
	}

	public function createParticle($player, $index=0){
		$level = $player->getLevel();
		$x = $this->setting->get("debt-x");
		$y = $this->setting->get("debt-y");
		$z = $this->setting->get("debt-z");
		$center = new Vector3($this->setting->get("debt-x"), $this->setting->get("debt-y"), $this->setting->get("debt-z"));
		if($index === 0){
			$radius = 0.5;
			$count = 100;
			$particle = new DustParticle($center, mt_rand(), mt_rand(), mt_rand(), mt_rand()); //ランダムな色を発生
			for($yaw = 0, $y = $center->y; $y < $center->y + 3; $yaw += (M_PI * 2) / 20, $y += 1 / 20){
				$x = -sin($yaw) + $center->x;
				$z = cos($yaw) + $center->z;
				$particle->setComponents($x, $y, $z);
				$level->addParticle($particle); //パーティクル発生
			}
		}elseif($index === 1){
			for($i = 0;$i <= 360;$i += 1){
				$rc = 2;
				$rm = 0.5;
				$rd = 1;
				$theta = $i;
				$x1 = ($rc - $rm) * cos($theta) + $rd * cos(($rc - $rm / $rm) * $theta);
				$this->main->x = $x1 + $x;
				$z1 = ($rc - $rm) * sin($theta) + $rd * sin(($rc - $rm / $rm) * $theta);
				$this->main->z = $z1 + $z;
				$particle = new FlameParticle(new Vector3($this->main->x, $y + 1, $this->main->z));
				$level->addParticle($particle);
			}
		}elseif($index === 2){
			for($i = 0; $i <= 2 * $pi; $i += $pi / 8){
				$x = $time * cos($i);
				$y = exp(-0.1 * $time) * sin($time) + 1.5;
				$z = $time * sin($i);
				$level->addParticle(new SmokeParticle($location->add($x, $y, $z)));
				$level->addParticle(new WaterDripParticle($location->add($x, $y, $z)));
			}
		}
	}
}