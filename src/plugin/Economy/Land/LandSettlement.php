<?php

namespace plugin\Economy\Land;

use pocketmine\Player;
use plugin\Economy\MoneyListener;


class LandSettlement {


    const RESULT_TYPE_SUCCESS = 0;
    const RESULT_TYPE_NOT_ENOUGH = 1;

    /** @var Player $player */
    private $player;

    /** @var int $area (x*z) */
    private $area;

    /** @var MoneyListener $moneyListener */
    private $moneyListener;


    public function __construct(Player $player, int $area){
        $this->player = $player;
        $this->area = $area;
        $this->moneyListener = new MoneyListener($player->getName());
    }


    public function getMoneyListener(): MoneyListener{
        return $this->moneyListener;
    }


    public function getCostPerArea(): int{
        return 1;
    }


    public function getCost(): int{
        return $this->area * $this->getCostPerArea();
    }


    public function buyArea(): int{
        $cost = $this->getCost();
        if($this->moneyListener->getMoney() <= $cost){
            $this->moneyListener->reduceMoney($cost);
            return self::RESULT_TYPE_SUCCESS;
        }else{
            return self::RESULT_TYPE_NOT_ENOUGH;
        }
    }
}