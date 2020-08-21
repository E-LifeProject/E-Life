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
       $data = $this->getConfig()->get("storehouse");
       return $data[$itemName];
    }

    public function addItemCount($itemName,$count){
        $config = $this->getConfig()->get("storehouse");
        $all = $config[$itemName];
        $all += $count;
        $this->getConfig()->setNested("storehouse.".$itemName,$all);
        $this->save();
    }

    public function reduceItemCount($itemName,$count){
        $config = $this->getConfig()->get("storehouse");
        $all = $config[$itemName];
        $all -= $count;
        $this->getConfig()->setNested("storehouse.".$itemName,$all);
        $this->save();
    }



    private function save(){
        $this->getConfig()->save();
    }

    private function getConfig(){
        $config = ConfigBase::getFor(ConfigList::GORVERNMENT);
        return $config;
    }
}
?>