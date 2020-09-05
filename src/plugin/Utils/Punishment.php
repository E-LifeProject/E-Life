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

        if($config->exists($target)){//既にペナルティが付与されている場合
            $data = "1/".$config->getNested($target.".Reason.1")."2/".$reason;
            Server::getInstance()->getNameBans()->addBan($target,$data,null,$source);
            $config->remove($target);
        }else{//ペナルティ初回付与時
            $config->setNested($target.".Count",1);
            $config->setNested($target.".Reason.1",$reason);
        }
        $config->save();
    }
    

    //ペナルティを解除
    public function cancelPunishment($target){
        $config = $this->getConfig();
        $config->remove($target);
        $config->save();
    }

    //警告理由
    public function getReason($name){
        return $this->getConfig()->getNested($name.".Reason");
    }

    //警告対象のチェック
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