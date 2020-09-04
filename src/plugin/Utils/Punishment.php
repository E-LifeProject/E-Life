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
     * 違反警告3回で強制的にBanされる
     */

    static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Punishment();
        }
        return self::$instance;
    }

     //ペナルティを追加
    public function addPunishment($target,$reason,$source){
        $config = $this->getConfig();

        if(!Server::getInstance()->getNameBans()->isBanned($target)){

            //configに存在していたら最低でもカウントは1
            if($config->exists($target)){
                $config->setNested($target.".Count",2);
                $config->setNested($target.".Reason.2",$reason);
                Server::getInstance()->getNameBans()->addBan($target,"計2回の警告により入室禁止",null,$source);
            }else{
                $config->setNested($target.".Count",1);
                $config->setNested($target.".Reason.1",$reason);
            }
            $config->save();
        }
    }
    
    //ペナルティを解除
    public function cancelPunishment($target,$number,$source){
        $config = $this->getConfig();

        if($number == 1){
            $data = $config->getNested($target.".Reason.2");
            $config->removeNested($target.".Reason.2");
            $config->setNested($target.".Reason.1",$data);
        }
        
        if($config->getNested($target.".Count" === 2)){
            Server::getInstance()->getNameBans()->remove($target);
            $config->removeNested($target.".Reason.".$number);
            $config->setNested($target.".Count",1);
        }else{
            $config->remove($target);
        }
    }

    public function getReason($name){
        return $this->getConfig()->getNested($name.".Reason");
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