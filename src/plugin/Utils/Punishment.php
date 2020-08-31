<?php

namespace plugin\Utils;

#Basic
use pocketmine\Player;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


class Punishment{

    /**
     * 後から理由も保存できるように
     * 連想配列にしてある
     */

     //ペナルティを追加
    public function addPunishment($player,$count){
        $name = $player->getName();
        
        if($this->getConfig()->exists($name)){
            $point = $this->getConfig()->getNested($name."Count");
            $point += $count;
            if($point >= 3){
                $player->setBanned();
                $this->getConfig()->remove($name);
            }else{
                $this->getConfig()->setNested($name.".Count",$point);
            }
            $this->getConfig()->save();
        }else{
            $this->getConfig()->setNested($name.".Count",$count);
            $this->getConfig()->save();
        }
    }

    //ペナルティを解除
    public function cancelPunishment($player,$count){
        $name = $player->getName();

        $point = $this->getConfig()->getNested($name.".Count");
        $point -= $count;
        if($point == 0){
            $this->getConfig()->remove($name);
        }else{
            $this->getConfig()->setNested($name.".Count",$point);
        }
        $this->getConfig()->save();
    }


    private function getConfig(){
        return ConfigBase::getFor(ConfigList::PUNISHMENT);
    }
}