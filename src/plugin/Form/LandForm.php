<?php

namespace plugin\Form;

use pocketmine\Player;
use pocketmine\form\Form;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use plugin\Economy\Land\LandSettlement;

class LandForm implements Form {

    private $positionHolder;

    public function __construct(){
        $this->positionHolder = PositionHolder::getInstance();
    }


    public function handleResponse(Player $player, $data): void{
        if($data === null)
            return;

        //$pos = $player->asVector3()->floor();
        switch($data){
            case 0:
                $player->sendForm(new LandSubForm(LandSubForm::FORM_TYPE_POS1, $player));
                break;
            case 1:
                if($this->positionHolder->getPos1($player) == null){
                    $player->sendMessage("土地保護INFO > §c先にpos1を設定してください");
                    break;
                }
                $player->sendForm(new LandSubForm(LandSubForm::FORM_TYPE_POS2, $player));
                break;
            default:
               //error
        }
    }


    public function jsonSerialize(): array{
        return [
            'type'=>'form',
            'title'=>'土地保護メニュー',
            'content'=>'選択してください',
            'buttons'=>[
                [
                    'text'=>'pos1'
                ],
                [
                    'text' =>'pos2'
                ]
            ]
        ];
    }
}

class LandSubForm implements Form {

    const FORM_TYPE_POS1 = "pos1";
    const FORM_TYPE_POS2 = "pos2";

    private $positionHolder;

    /** @var string **/
    private $type;

    /** @var Vector3 **/
    private $position;

    /** @var string $content */

    public function __construct(string $type, Player $player){
        $this->positionHolder = PositionHolder::getInstance();
        $this->type = $type;
        $this->player = $player;
        $levelName = $player->getLevel()->getName();
        $pos = $player->asVector3()->floor();
        switch($type){
            case self::FORM_TYPE_POS1:
                $this->content = $type . " [" . $levelName . "] (x=" . $pos->x . ", z=" . $pos->z . ") に設定しますか?";
            break;
            case self::FORM_TYPE_POS2:
                $xz = (function($positionHolder, $player){
                    $pos1 = $positionHolder->getPos1($player);
                    var_dump($pos1);
                    $pos2 = $player->asPosition();
                    return $pos1->abs()->subtract($pos2->abs())->abs();
                })($this->positionHolder, $player);
                $area = $xz->x * $xz->z;
                $this->landSettlement = new LandSettlement($player, $area);
                $cost = $this->landSettlement->getCost();
                $this->content = $type . " [" . $levelName . "] (x=" . $pos->x . ", z=" . $pos->z . ") に設定し\n" . 
                                 "合計金額 : $" . $cost . " で購入しますか?";
            break;

        }
    }

    public function handleResponse(Player $player, $data): void{
        if($data === null)
            return;
        if($data){
            switch($this->type){
                case self::FORM_TYPE_POS1:
                    $this->positionHolder->setPos1($this->player);
                    $player->sendMessage("地保護INFO > §a pos1を設定しました");
                break;
                case self::FORM_TYPE_POS2:
                    if(!$this->positionHolder->setPos2($this->player)){
                        $player->sendMessage("土地保護INFO > §cpos1とpos2のワールドが一致しません");
                        break;
                    }
                    $result = $this->landSettlement->buyArea();
                    switch($result){
                        case LandSettlement::RESULT_TYPE_SUCCESS:
                            $player->sendMessage("土地保護INFO > §a 土地保護が完了しました");
                        break;
                        case LandSettlement::RESULT_TYPE_NOT_ENOUGH:
                            $player->sendMessage("土地保護INFO > §c 所持金が足りませんでした");
                        break;
                    }
                    
            }
        }
    }

    public function jsonSerialize(): array{
        return  [
            'type' => 'modal',
            'title' => '確認画面',
            'content' => $this->content,
            "button1" => "§bはい",
            "button2" => "§cキャンセル"
        ];
    }
}

/**
 * Singleton class
 */
class PositionHolder {

    private static $instance = null;

    /** @var  array $select */
    private $positions;

    private function __construct(){
        //nothing to do...
    }


    public static function getInstance(): self{
        if(self::$instance == null)
            self::$instance = new self;
        return self::$instance;
    }


    public function setPos1(Player $player): bool{
        $name = $player->getName();
        $this->positions[$name]["pos1"] = $player->asPosition();
        return true;
    }


    public function setPos2(Player $player): bool{
        $name = $player->getName();
        if($player->getLevel()->getName() != $this->getPos1($player)->getLevel()->getName()){
            return false;
        }
        $this->positions[$name]["pos2"] = $player->asPosition();
        return true;
    }


    public function getPos1(Player $player): ?Position{
        $name = $player->getName();
        return isset($this->positions[$name]["pos1"]) ? $this->positions[$name]["pos1"] : null;
    }


    public function getPos2(Player $player): ?Position{
        $name = $player->getName();
        return isset($this->positions[$name]["pos2"]) ? $this->positions[$name]["pos2"] : null;
    }
}