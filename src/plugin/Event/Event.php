<?php

namespace plugin\Event;

#Basic
use DateTime;
use pocketmine\event\Listener;

#Event
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

#Packet
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;

#E-Life
use plugin\form\TermsForm;
use plugin\NPC\NPC;
use plugin\NPC\FloatText;
use plugin\item\OriginItemFactory;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Config\PlayerConfigBase;
use plugin\Config\Data\JobCount;
use plugin\Item\Original\MenuBook;
use plugin\Main;

class Event implements Listener {

	/** @var Main */
	private $main;

	/** @var OriginItemFactory */
	private $origin_item_factory;

    public function __construct(Main $main) {
        $this->main = $main;
        $this->origin_item_factory = new OriginItemFactory();

        $this->status_config = ConfigBase::getFor(ConfigList::STATUS_NPC);
        $this->status_text = new FloatText($this->status_config);
    }

    public function onLogin(PlayerLoginEvent $event) {
    	$player = $event->getPlayer();
        $name = $player->getName();

	    //E-Clubの加入状況確認
	    $club = ConfigBase::getFor(ConfigList::CLUB);

	    $club->reload();
        if($club->exists($name)) {
	        $date1 = new DateTime($club->get($name));
	        $date2 = new DateTime(date("Y/m/d"));
	        if($date1 < $date2)
		        $club->__unset($name);
        }

        $this->eid = $this->status_text->getStatusNpcEid($player);

        PlayerConfigBase::init($this->main, $name);
    }


    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //OPには[権限者]をつける
        if($player->isOp()){
            $player->setNameTag("§9♪"."§f".$name);
            $player->setDisplayName("§9♪"."§f".$name);
        }
        
        //初回ログインには利用規約への同意確認フォームを送る
	    $player_config = ConfigBase::getFor(ConfigList::PLAYER);

        $player_config->reload();
        if(!$player_config->exists($name)) {
            $player->sendForm(new TermsForm());
        }

        //ログインしたらTitle表示
        $player->sendTitle("E-Life鯖へようこそ","Welcome to E-Life",40,40,40);

        //ログインメッセージの変更
        $event->setJoinMessage("§6[全体通知] §7".$name."さんがE-Lifeにログインしました");

        $player->getInventory()->setItem(0, new MenuBook());

        $npc = new NPC($this->status_config);
        $npc->showNPC($player, $this->main->npc, 155, 155);
        $this->status_text->showText($player, $this->eid);
    }


    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //ログアウトメッセージの変更
        $event->setQuitMessage("§6[全体通知] §7".$name."さんがE-Lifeからログアウトしました");

        unset($this->eid[$name]);
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
        } elseif ($pk instanceof InteractPacket){
            $player = $event->getPlayer();
            $eid = $pk->target;
            if($eid === null){
                return false;
            }

            if($eid === $this->main->npc){
                $this->status_text->showText($player, $this->eid);
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