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

    //初回ログイン時に実行（初期信頼度は10)
    public function initialCreation(){
        $this->setConfig(10);
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
            case 40 < $count && $count <= 50:
                return §e;
            break;
            case 50 < $count && $count <= 60:
                return §b;
            break;
            case 60 < $count && $count <= 70:
                return §a;
            break;
            case 70 < $count && $count <= 80:
                return §c;
            break;
            case 80 < $count && $count <= 90:
                return §d;
            break;
            case 90 < $count && $count <= 100:
                return §6;
            break;
        }
    }

    /**
     * ----信頼度計算項目----
     * 1.プレイ時間
     * 2.ローン返済状況
     * 3.権限or役職
     * 4.保有土地面積(共有は除く)
     * 5.鯖ルール違反数
     * 6.一週間のチャット数
     * 7.E-Club加入状況
     * 8.鯖貢献度（管理者が手動で設定)初期値が10
     */

    //信頼度の計算を行う
    public function reliabilityCalculation(){
        /**
         * プレイ時間に応じて点数を決定
         * 0:総プレイ時間2時間未満
         * 1:総プレイ時間2時間以上4時間未満
         * 2:総プレイ時間4時間以上8時間未満
         * 3:総プレイ時間8時間以上10時間未満
         * 4:総プレイ時間10時間以上15時間未満
         * 5:総プレイ時間15時間以上20時間未満
         * 6:総プレイ時間20時間以上30時間未満
         * 7:総プレイ時間30時間以上40時間未満
         * 8:総プレイ時間40時間以上60時間未満
         * 9:総プレイ時間60時間以上100時間未満
         * 10:総プレイ時間100時間以上
         */
        $time = ConfigBase::getFor(ConfigList::TIME)->get($name);
        $hours = floor($time / 3600);
        switch($hours){
            case 0 <= $hours && $hours < 2:
                $point += 0;
            break;
            case 2 <= $hours && $hours < 4:
                $point += 1;
            break;
            case 5<= $hours && $hours < 8:
                $point += 2;
            break;
            case 8 <= $hours && $hours < 10:
                $point += 3;
            break;
            case 10 <= $hours && $hours < 15:
                $point += 4;
            break;
            case 15 <= $hours && $hours < 20:
                $point += 5;
            break;
            case 20 <= $hours && $hours < 30:
                $point += 6;
            break;
            case 30 <= $hours && $hours < 40:
                $point += 7;
            break;
            case 40 <= $hours && $hours < 60:
                $point += 8;
            break;
            case 60 <= $hours && $hours < 100:
                $point += 9;
            break;
            case 100 <= $hours:
                $point += 10;
            break;
        }

        /**
         * ローンの返済状況に応じて点数を決定
         */
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