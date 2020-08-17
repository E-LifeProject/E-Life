<?php

namespace plugin\Event;

#Basic
use DateTime;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;


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
use plugin\Form\TermsForm;
use plugin\NPC\StatusNPC;
use plugin\NPC\FloatText;
use plugin\Item\OriginItemFactory;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Config\PlayerConfigBase;
use plugin\Config\Data\JobCount;
use plugin\Item\Original\MenuBook;
use plugin\Main;
use plugin\NPC\GovernmentNPC;
use plugin\Form\GovernmentMenu;
use plugin\Form\BankMenu;
use plugin\NPC\ATMFloatText;


class Event implements Listener {

	/** @var Main */
	private $main;

	/** @var OriginItemFactory */
	private $origin_item_factory;

    public function __construct(Main $main) {
        $this->main = $main;
        $this->origin_item_factory = new OriginItemFactory();

        $this->status_text = new FloatText();

        $this->GovernmentNPC = new GovernmentNPC($main->skin);
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
	        if($date1 < $date2){
                $club->__unset($name);
            }
        }

        //StatusNPCで表示する項目を取得
        $this->eid = $this->status_text->getStatusNpcEid($player);

        //Configの生成処理など
        PlayerConfigBase::init($this->main, $name);
    }


    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //OPには♪をつける
        if($player->isOp()){
            $player->setNameTag("§9♪"."§f".$name);
            $player->setDisplayName("§9♪"."§f".$name);
        }

        /**
         * 利用規約などを変更した時に、リストを削除して
         * 送信者をリセット出来るように
         */
        
        //初回ログインには利用規約への同意確認フォームを送る
	    $player_config = ConfigBase::getFor(ConfigList::PLAYER);

        $player_config->reload();
        if(!$player_config->exists($name)) {
            $player->sendForm(new TermsForm());
        }

        
        $player->sendTitle("E-Life鯖へようこそ","Welcome to E-Life",40,40,40);
        $event->setJoinMessage("§6[全体通知] §7".$name."さんがE-Lifeにログインしました");

        //MenuBookをインベントリに追加
        $player->getInventory()->setItem(0, new MenuBook());

        //StatusNPCを表示
        $npc = new StatusNPC();
        $npc->showNPC($player, $this->main->StatusNPC, 155, 155);
        $this->status_text->showText($player, $this->eid);

        //GovernmentNPCを表示
        $this->GovernmentNPC->showNPC($player,$this->main->GovernmentNPC,175,120);

        //ATMの浮き文字を表示
        $float = new ATMFloatText();
		$float->FloatText($player);
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

        //MenuBookでタップしたらMainMenuを表示
        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            $this->getOriginItemFactory()->useFor($player, $event->getItem());
        }

        //ATMをタップしたら銀行メニューを表示
        if($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK || $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
			$block = $event->getBlock();
            $level = $block->getLevel();
            $target_block = $level->getBlock(new Vector3(228.5, 9, 232.5));

			if($target_block === $block){
				$player = $event->getPlayer();
				$player->sendForm(new BankMenu($this->main)); 
			}
		}
    }

    public function onReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        
        /**
         * MenuBookをインベントリスロットの位置を固定
         * Playerがスロットを変更しようとしたらイベントキャンセル
         */

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

            if($eid === $this->main->StatusNPC){
                $this->status_text->showText($player, $this->eid);
            }elseif($eid === $this->main->GovernmentNPC){
                $player->sendForm(new GovernmentMenu());
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