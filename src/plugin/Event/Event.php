<?php

namespace plugin\Event;

#Basic
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;
use pocketmine\block\BlockIds;
use pocketmine\utils\Config;
use pocketmine\form\Form;


#Event
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;


#Packet
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

#E-Life
use plugin\form\TermsForm;
use plugin\form\MainMenu;
use plugin\MenuItem;


class Event implements Listener{

    public function __construct($main){
        $this->main = $main;
        $this->menu = new MenuItem();
    }

    public function onLogin(PlayerLoginEvent $event){
        $name = $event->getPlayer()->getName();
        
        //E-Clubの加入状況確認
        $this->main->club->reload();
        if($this->main->club->exists($name)){
            $date1 = new \DateTime($this->main->club->get($name));
            $date2 = new \DateTime(date("Y/m/d"));
            if($date1 < $date2){
                $this->main->club->__unset($name);
                $this->main->club->save();
            }
        }

        //Jobの変更可能回数を記録
        if(!$this->main->jobCount->exists($name)){
            $this->main->jobCount->set($name,3);
            $this->main->jobCount->save();
        }
        $this->main->jobCountArray[$name] = $this->main->jobCount->get($name);
    }


    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //OPにはマークをつける
        if($player->isOp()){
            $player->setNameTag("§9◆"."§f".$name);
            $player->setDisplayName("§9◆"."§f".$name);
        }

        //初回ログインには利用規約への同意確認フォームを送る
        $this->main->player->reload();
        if(!$this->main->player->exists($name)){
            $player->sendForm(new TermsForm($this->main));
        }

        //ログインしたらTitle表示 
        $player->addTitle("E-Life鯖へようこそ","Welcome to E-Life",40,40,40);

        //ログインメッセージの変更
        $event->setJoinMessage("§6[全体通知] §7".$name."さんがE-Lifeにログインしました");

        $item = $this->menu->getMenuItem();
        $player->getInventory()->setItem(0, $item);
    }


    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //ログアウトメッセージの変更
        $event->setQuitMessage("§6[全体通知] §7".$name."さんがE-Lifeからログアウトしました");
        
        //Jobの変更可能回数をconfigの保存
        $this->main->jobCount->set($name,$this->main->jobCountArray[$name]);
        $this->main->jobCount->save();
    }


    public function onTap(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //本でタップしたらMainMenuを表示する
        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            $item_name = $event->getItem()->getCustomName();
            $menu_item_name = $this->menu->getMenu_ItemName();
            if($item_name === $menu_item_name){
                $player->sendForm(new MainMenu($this->main));
            }
        }
    }

    public function onReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        if($pk instanceof InventoryTransactionPacket){
            if(isset($pk->actions[0])){
                $slot = $pk->actions[0]->inventorySlot;
                if($slot === 0){
                    $event->setCancelled();
                }
            }
        }
    }

    //Jobコマンドを使えないようにする
    public function PlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $sub = substr($message, 0, 1);
        if($sub === '/'){
            if(strpos($message, '/job') !== false){
                $event->setCancelled();
                $player->sendMessage("§a[個人通知] §7このコマンドは利用することが出来ません");
            }
        }
    }
}
?>