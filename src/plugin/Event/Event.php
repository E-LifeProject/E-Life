<?php

namespace plugin\Event;

#Basic
use DateTime;
use OriginItemFactory;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Main;
use pocketmine\event\Listener;

#Event
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

#Packet
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

#E-Life
use plugin\form\TermsForm;


class Event implements Listener {

	/** @var Main */
	private $main;

	/** @var OriginItemFactory */
	private $origin_item_factory;

    public function __construct(Main $main) {
        $this->main = $main;
        $this->origin_item_factory = new OriginItemFactory();
    }

    public function onLogin(PlayerLoginEvent $event){
        $name = $event->getPlayer()->getName();

	    //E-Clubの加入状況確認
	    $club = ConfigBase::getFor(ConfigList::CLUB);

	    $club->reload();
        if($club->exists($name)) {
	        $date1 = new DateTime($club->get($name));
	        $date2 = new DateTime(date("Y/m/d"));
	        if($date1 < $date2)
		        $club->__unset($name);
        }

        //Jobの変更可能回数を記録
	    $job_count = ConfigBase::getFor(ConfigList::JOB_COUNT);

        if(!$job_count->exists($name)) {
            $job_count->set($name, 3);
            $job_count->save();
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
        $player->sendTitle("E-Life鯖へようこそ","Welcome to E-Life",40,40,40);

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

        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK)
        	$this->getOriginItemFactory()->useFor($player, $event->getItem());
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

	/** @return Main */
	private function getMain(): Main {
		return $this->main;
	}

	/** @return OriginItemFactory */
	 private function getOriginItemFactory(): OriginItemFactory {
		return $this->origin_item_factory;
	}
}