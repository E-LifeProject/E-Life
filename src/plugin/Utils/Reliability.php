<?php

namespace plugin\Utils;

#Basic
use pocketmine\utils\Config;
use pocketmine\Server;
use DateTime;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Economy\Bank;


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
        return $this->getConfig()->getNested($this->name.".Total");
    }

    //信頼度の色の§を返す
    public function getReliabilityColour(){
        $count = $this->getReliability();

        switch($count){

            case 0 < $count && $count <= 10:
                return "§8";
            break;
            case 10 < $count && $count <= 20:
                return "§f";
            break;
            case 20 < $count && $count <= 30:
                return "§3";
            break;
            case 30 < $count && $count <= 40:
                return "§2";
            break;
            case 40 < $count && $count <= 50:
                return "§e";
            break;
            case 50 < $count && $count <= 60:
                return "§b";
            break;
            case 60 < $count && $count <= 70:
                return "§a";
            break;
            case 70 < $count && $count <= 80:
                return "§c";
            break;
            case 80 < $count && $count <= 90:
                return "§d";
            break;
            case 90 < $count && $count <= 100:
                return "§6";
            break;
        }
    }

    /**
     * ----信頼度計算項目----
     * 1.プレイ時間/10　　　長期的な要素
     * 2.ローン返済状況/10
     * 3.権限or役職/5
     * 4.保有土地面積(共有は除く)
     * 5.鯖ルール違反数/10
     * 6.一週間のチャット数(初回ログインの最初の一週間は0)　直近の要素
     * 7.E-Club加入状況/5
     * 8.鯖貢献度（管理者が手動で設定)初期値が10/30
     */

    //信頼度の計算を行う
    public function reliabilityCalculation(){
        $playTime = $this->playTimeCalculation();
        $loan = $this->loanCountCalculation();
        $club = $this->clubCalculation();
        $punishment = $this->violationCalculation();
        $authority = $this->authorityCalculation();
        $chat = $this->chatCalculation();

        $total = $playTime + $loan + $club + $punishment + $authority + $chat;

        $config = $this->getConfig();

        //鯖貢献度(初期値)
        if(!$config->exists($this->name)){//初回ログイン時
           $total += 10;
           $config->setNested($this->name.".Manual",10);

        }else{
            $total += $config->getNested($this->name.".Manual");
        }

        $config->setNested($this->name.".Total",intval($total));
        $config->save();
    }


    //プレイ時間に応じて点数を付与
    private function playTimeCalculation(){
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
        $time = ConfigBase::getFor(ConfigList::TIME)->get($this->name);
        $hours = floor($time / 3600);
        if(0 <= $hours && $hours < 2){
            $point = 0;
        }elseif(2 <= $hours && $hours < 4){
            $point = 1;
        }elseif(5<= $hours && $hours < 8){
            $point = 2;
        }elseif(8 <= $hours && $hours < 10){
            $point = 3;
        }elseif(10 <= $hours && $hours < 15){
            $point = 4;
        }elseif(15 <= $hours && $hours < 20){
            $point = 5;
        }elseif(20 <= $hours && $hours < 30){
            $point = 6;
        }elseif(30 <= $hours && $hours < 40){
            $point = 7;
        }elseif(40 <= $hours && $hours < 60){
            $point = 8;
        }elseif(60 <= $hours && $hours < 100){
            $point = 9;
        }elseif(100 <= $hours){
            $point = 10;
        }
        var_dump($point);
        return $point;
    }

    //ローン返済回数に応じて点数を付与（期限を超過した返済の場合はカウントされない)
    private function loanCountCalculation(){
        /**
         * ローン返済回数に応じて点数を付与
         * 0:0回、もしくは現在返済途中など
         * 3:1回の返済を完了
         * 5:2回の返済を完了
         * 8:3回or4回の返済を完了
         * 10:5回以上の返済を完了
         */

        if(Bank::getInstance()->checkAccount($this->name)){
            $count = Bank::getInstance()->getCount($this->name);
            $point = 0;
            switch($count){
                case 0:
                    $point = 0;
                break;
                case 1:
                    $point = 3;
                break;
                case 2:
                    $point = 5;
                break;
                case 3 or 4:
                    $point = 8;
                break;
                case 5 <= $count:
                    $point = 10;
                break;
            }
        }else{
            $point = 0;
        }

        var_dump($point);
        return $point;
    }

    //E-Clubに加入状況に応じて付与
    private function clubCalculation(){
        /**
         * 加入状況に応じて付与
         * 未加入:0
         * 加入済み:5
         */
        $point = 0;
        $club = ConfigBase::getFor(ConfigList::CLUB);
        if($club->exists($this->name)){
            $point = 5;
        }else{
            $point = 0;
        }

        var_dump($point);
        return $point;
    }


    //違反回数に応じて減点していく
    private function violationCalculation(){

        /**
         * 違反回数に応じて減点(3回の警告で入室禁止)
         * 10:違反なし
         * 2:1回の違反
         * 0:２回の違反
         */
        $config = ConfigBase::getFor(ConfigList::PUNISHMENT);
        if($config->exists($this->name)){
            $count = $config->getNested($this->name.".Count");
            switch($count){
                case 1:
                    $point = 2;
                break;
                case 2:
                    $point = 0;
                break;
            }
        }else{
            $point = 10;
        }
        var_dump($point);
        return $point;
    }

    //権限に応じて加点
    private function authorityCalculation(){

        /**
         * 権限に応じて加点
         * 5:OP
         */

        if(Server::getInstance()->isOp($this->name)){
            $point = 5;
        }else{
            $point = 0;
        }
        var_dump($point);
        return $point;
    }

    //一週間のチャット数に応じて加点
    private function chatCalculation(){
        $config = ConfigBase::getFor(ConfigList::CHATCOUNT);
        $configReliability = $this->getConfig();

        if($config->exists($this->name)){
            $date1 = new DateTime($config->getNested($this->name.".Start"));
            $date2 = new DateTime(date("Y/m/d"));
            $date3 = new DateTime($config->getNested($this->name.".End"));

            if($date1 < $date2){
                if($date2 > $date3){
                    $configReliability->setNested($this->name.".Chat",$config->getNested($this->name.".Count"));
                    $configReliability->save();
                }else{
                    $config->setNested($this->name.".Count",0);
                    $configReliability->setNested($this->name.".Chat",0);
                    $config->setNested($this->name.".Start",date("Y/m/d",strtotime("7 day")));//一週間後
                    $config->setNested($this->name.".End",date("Y/m/d",strtotime("14 day")));//二週間後
                }
            }else{
                $config->setNested($this->name.".Count",0);
                $configReliability->setNested($this->name.".Chat",0);
                $config->setNested($this->name.".Start",date("Y/m/d",strtotime("7 day")));//一週間後
                $config->setNested($this->name.".End",date("Y/m/d",strtotime("14 day")));//二週間後
            }
        }else{
            $config->setNested($this->name.".Count",0);
            $configReliability->setNested($this->name.".Chat",0);
            $config->setNested($this->name.".Start",date("Y/m/d",strtotime("7 day")));//一週間後
            $config->setNested($this->name.".End",date("Y/m/d",strtotime("14 day")));//二週間後
        }
        $configReliability->save();
        $config->save();
    }

    //鯖貢献度（手動部分）
    public function setManual($point){
        $config = $this->getConfig();

        if($config->exists($this->name)){
            $config->setNested($this->name.".Manual",intval($point));
            $config->save();
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