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
    public function addPunishment($target,$count,$reason,$source){
        $config = $this->getConfig();

        if($config->exists($target)){//既に警告がされている場合
            $total = $config->getNested($target.".Count") + $count;
            if($total >= 3){//3回目の警告で自動的に入室禁止
                $reasonCount = Count($config->getNested($target.".Reason"));
                switch($reasonCount){
                    case 1:
                        $data = "1:".$config->getNested($target.".Reason.1")."2:".$reason;
                    break;
                    case 2:
                        $data = "1:".$config->getNested($target.".Reason.1")."2:".$config->getNested($target.".Reason.2")."3:".$reason;
                    break;
                }
                Server::getInstance()->getNameBans()->addBan($target,$data,null,$source);
                $config->remove($target);
            }else{//警告回数が2回の時
                $config->setNested($target.".Count",$total);
                $config->setNested($target.".Reason.2",$reason);
            }
        }else{//初回警告(初回で3回警告はするな。入室禁止にさせる)
            $config->setNested($target.".Count",$count);
            $config->setNested($target.".Reason.1",$reason);
        }

        $config->save();
    }
    

    //ペナルティを解除
    public function cancelPunishment($target){
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