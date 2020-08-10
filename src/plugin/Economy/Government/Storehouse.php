<?php

namespace plugin\Economy\Government;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

/**
 * 買取したものなどを保管する(ここに溜まったブロックはショップで販売するか、公共事業などで使用する)
 * 政府倉庫のクラス
 */

class Storehouse{

    static $instance;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Storehouse();
        }
        return self::$instance;
    }

    public function getItemCount($itemName){
       $item = $this->getConfig()->get($itemName);
       return $item['storehouse'];
    }

    public function addItemCount($itemName,$count){
        $config = $this->getConfig()->get($itemName);
        $all = $config['storehouse'];
        $all += $count;
        $this->getConfig()->setNested($itemName.".storehouse",$all);
        $this->save();
    }

    public function reduceItemCount($itemName,$count){
        $config = $this->getConfig()->get($itemName);
        $all = $config['storehouse'];
        $all -= $count;
        $this->getConfig()->setNested($itemName.".storehouse",$all);
        $this->save();
    }



    private function save(){
        $this->getConfig()->save();
    }

    private function getConfig(){
        $config = ConfigBase::getFor(ConfigList::PURCHASE);
        return $config;
    }
}
?>