<?php

namespace plugin\Utils;

#Basic
use pocketmine\Player;
use pocketmine\Server;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


class Punishment{

    static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Punishment();
        }
        return self::$instance;
    }

     //ペナルティを追加
    public function addPunishment($target,$count,$reason,$source){
        
        if($this->getConfig()->exists($target)){
                $point = $this->getConfig()->getNested($target.".Count");
                $point += $count;

                if($point = 3){//もし警告上限回数なら
                    $reasonArray = $this->getConfig()->getNested($target.".Reason");
                    $arrayCount = count($reasonArray);

                    switch($arrayCount){
                        case 1:
                            $text = "1.".$reasonArray[1]."\n2.".$reason;
                        break;
                        case 2:
                            $text = "1.".$reasonArray[1]."\n2.".$reasonArray[2]."\n3.".$reason;
                        break;
                    }

                    Server::getInstance()->getNameBans()->addBan($target,$text,null,$source);
                    $this->getConfig()->remove($target);
                    $this->getConfig()->save();
                }else{
                    $this->getConfig()->setNested($target.".Count",$point);
                    $this->getConfig()->setNested($target.".Reason.2",$reason);
                    $this->getConfig()->save();
                }
        }else{
            if(!Server::getInstance()->getNameBans()->isBanned($name)){
                $this->getConfig()->setNested($target.".Count",$count);
                $this->getConfig()->setNested($target.".Reason.1",$reason);
                $this->getConfig()->save();
            }
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

    public function checkPunishment($name){
        if($this->getConfig()->exists($name)){
            return true;
        }else{
            return false;
        }
    }


    private function getConfig(){
        return ConfigBase::getFor(ConfigList::PUNISHMENT);
    }
}