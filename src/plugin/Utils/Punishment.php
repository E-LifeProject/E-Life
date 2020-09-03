<?php

namespace plugin\Utils;

#Basic
use pocketmine\Player;
use pocketmine\Server;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


class Punishment{

    /**
     * 後から理由も保存できるように
     * 連想配列にしてある
     */

    static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Punishment();
        }
        return self::$instance;
    }

     //ペナルティを追加
    public function addPunishment($name,$count){
        
        if($this->getConfig()->exists($name)){
            $point = $this->getConfig()->getNested($name.".Count");
            $point += $count;
            if($point >= 3){
                Server::getInstance()->getNameBans()->addBan($name,"警告が3回目に到達したのでBan");
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
    public function cancelPunishment($name,$count){

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