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
    public function addPunishment($name,$count,$reason){
        
        if($this->getConfig()->exists($name)){
            if($this->getConfig()->getNested($name.".Ban") === false){
                $point = $this->getConfig()->getNested($name.".Count");
                $point += $count;
                if($point = 3){
                    $this->getConfig()->setNested($name.".Count",3);
                    $this->getConfig()->setNested($name.".Reason.3",$reason);
                    Server::getInstance()->getNameBans()->addBan($name,"警告が3回目に到達したのでBan");
                    $this->getConfig()->setNested($name.".Ban",true);
                }else{
                    $this->getConfig()->setNested($name.".Count",$point);
                    $this->getConfig()->setNested($name.".Reason.".intval($point),$reason);
                }
                $this->getConfig()->save();
            }
        }else{
            $this->getConfig()->setNested($name.".Count",$count);
            $this->getConfig()->setNested($name.".Reason.".intval($count),$reason);
            $this->getConfig()->setNested($name.".Ban",false);
            $this->getConfig()->save();
        }
    }

    //ペナルティを解除
    public function cancelPunishment($name,$count){
        if($this->getConfig()->getNested($name.".Count") == 3){
            $this->getConfig()->removeNested($name.".Reason.3");
            $this->getConfig()->setNested($name.".Ban",false);
            $this->getConfig()->setNested($name.".Count",2);
            $this->getConfig()->save();
        }else{
            $point = $this->getConfig()->getNested($name.".Count");
            $point -= $count;
            if($point == 0){
                $this->getConfig()->remove($name);
            }else{
                $this->getConfig()->setNested($name.".Count",$point);
            }
            $this->getConfig()->save();
        }
    }


    private function getConfig(){
        return ConfigBase::getFor(ConfigList::PUNISHMENT);
    }
}