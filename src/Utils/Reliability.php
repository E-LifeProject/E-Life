<?php

namespace plugin\Utils;

#Basic
use pocketmine\utils\Config;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;


/**
 * 信頼度に関するクラス
 * 信頼度は0〜100を使って、表す
 * 信頼度が多ければローンも借りやすくなる
 * この数値原因でBanやKickなどの処罰が与えられることは絶対にない
 */

class Reliability{

    public function __construct($name){
        $this->name = $name;
    }

    /**
     * 信頼度ランク
     * 1 ~ 10 :Dark Gray
     * 11 ~ 20 :White
     * 21 ~ 30 :Dark Aqua
     * 31 ~ 40 :Dark Green
     * 41 ~ 50 :Yellow
     * 51 ~ 60 :Aqua
     * 61 ~ 70 :Green
     * 71 ~ 80 :Red
     * 81 ~ 90 :Light Purple
     * 91 ~ 100:Gold
     */

    //初回ログイン時に実行（初期信頼度は30)
    public function initialCreation(){
        $this->setConfig(30);
        $this->save();
    }

    //信頼度を返す
    public function getReliability(){
        return $this->getConfig()->get($this->name);
    }

    //信頼度の色の§を返す
    public function getReliabilityColour(){
        $count = $this->getReliability();

        switch($count){

            case 0 < $count && $count <= 10:
                return §8;
            break;

            case 10 < $count && $count <= 20:
                return §f;
            break;

            case 20 < $count && $count <= 30:
                return §3;
            break;

            case 30 < $count && $count <= 40:
                return §2;
            break;
        }
    }

    //信頼度を追加
    public function addReliability($count){
        $total = $this->getReliability();
        $total += $count;
        $this->setConfig($total);
        $this->save();
    }

    //信頼度を減少
    public function reduceReliability($count){
        $total = $this->getReliability();
        $total -= $count;
        $this->setConfig($total);
        $this->save();
    }





    private function getConfig(){
        return ConfigBase::getFor(ConfigList::RELIABILITY);
    }

    private function setConfig($count){
        $this->getConfig()->set($this->name,$count);
    }

    private function save(){
        $this->getConfig()->save();
    }
}