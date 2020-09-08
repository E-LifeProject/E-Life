<?php

namespace plugin\Event;

#Basic
use DateTime;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;


#Event
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

#Packet
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;

#E-Life
use plugin\Form\TermsForm;
use plugin\Economy\Bank;
use plugin\NPC\StatusNPC;
use plugin\NPC\FloatText;
use plugin\Item\OriginItemFactory;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Config\PlayerConfigBase;
use plugin\Config\Data\JobCount;
use plugin\Item\Original\MenuBook;
use plugin\Utils\Reliability;
use plugin\Main;
use plugin\NPC\GovernmentNPC;
use plugin\Form\GovernmentMenu;
use plugin\Form\BankMenu;
use plugin\NPC\ATMFloatText;
use plugin\Utils\Punishment;


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

    public function onLogin(PlayerLoginEvent $event){
    	$player = $event->getPlayer();
        $name = $player->getName();
        
        

        //総プレイ時間記録の為の初期化
        $this->main->time[$name] = 0;

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

        /**
         * ローン支払い期日が過ぎている場合に
         * 名前の横に⚠︎を付ける（これが付いている場合、信用が落ち鯖内で暮らしにくくなる）
         * ただし、ローンの返済期日の表示はそのままで返済はしてもらう
         */

        //ローンの状況を確認する
        $bank = Bank::getInstance();
        
        if($bank->checkLoan($name)){
            $loanDate1 = new DateTime($bank->getLoanDate($name));
            $loanDate2 = new DateTime(date("Y/m/d"));
            if($loanDate1 < $loanDate2){
                if($bank->getLoan($name) > 0){
                    if(!ConfigBase::getFor(ConfigList::LOAN_PENALTY)->exists($name)){
                        $bank->addPenalty($name);
                        $punishment = new Punishment();
                        $punishment->addPunishment($player,1);
                    }
                }
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


        $xuid = $player->getXuid();
        $config = ConfigBase::getFor(ConfigList::XUID);

        
        if($config->exists($xuid)){
            if($config->get($xuid) !== $name){
                $player->kick("名前が異なる為ログインする事ができません",false);
                return;
            }
        }else{
            $config->set($xuid,$name);
            $config->save();
        }

        //MenuBookをインベントリに追加
        $player->getInventory()->setItem(0, new MenuBook());

        if($player->isOp()){
            $player->setNameTag("§9♪§f".$name);
            $player->setDisplayName("§9♪§f".$name);
        }

        
        //ルール違反者もしくは、ローン支払い出来なかった人には注意マークを付ける
        if(ConfigBase::getFor(ConfigList::PUNISHMENT)->exists($name)){
            if(!$player->isOp()){
                $count = ConfigBase::getFor(ConfigList::PUNISHMENT)->getNested($name."Count");
                switch($count){
                    case 1:
                        $player->setNameTag("§9⚠︎§f".$name);
                        $player->setDisplayName("§9⚠︎§f".$name);
                    break;
                    case 2:
                        $player->setNameTag("§9⚠︎⚠︎§f".$name);
                        $player->setDisplayName("§9⚠︎⚠︎§f".$name);
                    break;
                }
            }
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

        //StatusNPCを表示
        $npc = new StatusNPC();
        $npc->showNPC($player, $this->main->StatusNPC, 155, 155);
        $this->status_text->showText($player, $this->eid);

        //GovernmentNPCを表示
        $this->GovernmentNPC->showNPC($player,$this->main->GovernmentNPC,175,120);

        //ATMの浮き文字を表示
        $float = new ATMFloatText();
		$float->FloatText($player);

        $player->sendTitle("E-Life鯖へようこそ","Welcome to E-Life",40,40,40);
        $event->setJoinMessage("§6[全体通知] §7".$name."さんがE-Lifeにログインしました");
    }


    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        //ログアウトメッセージの変更
        $event->setQuitMessage("§6[全体通知] §7".$name."さんがE-Lifeからログアウトしました");

        unset($this->eid[$name]);
        //総プレイ時間を記録
        $config = ConfigBase::getFor(ConfigList::TIME);
        if($config->exists($name)){
            $data = $config->get($name);
            $data += $this->main->time[$name];
            $config->set($name,$data);
            $config->save();
        }else{
            $config->set($name,$this->main->time[$name]);
            $config->save();
        }
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

    //KeepInventory
    public function PlayerDeath(PlayerDeathEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        $config = ConfigBase::getFor(ConfigList::KEEP_INVENTORY);
        if($config->exists($name)){
            $date1 = new DateTime($config->get($name.".Date"));
            $date2 = new DateTime(date("Y/m/d"));
            if($date1 !== $date2){
                $config->setNested($name.".Date",date("Y/m/d"));
                $config->setNested($name.".Count",1);
                $config->save();
                $event->setKeepInventory(true);
                $player->sendMessage("§6[個人通知] §7KeepInventoryを使用しました。残り1回です");
            }else{
                if( 2 >= $config->getNested($name.".Count")){
                    switch($config->getNested($name.".Count")){
                        case 1:
                            $event->setKeepInventory(true);
                            $player->sendMessage("§6[個人通知] §7KeepInventoryを使用しました残り1回です");
                            $config->setNested($name.".Count",2);
                            $config->save();
                        break;

                        case 2:
                            $event->setKeepInventory(true);
                            $player->sendMessage("§6[個人通知] §7KeepInventoryを使用しました残り0回です");
                            $config->setNested($name.".Count",3);
                            $config->save();
                        break;
                    }
                }
            }
        }else{
           $config->setNested($name.".Count",1);
           $config->setNested($name.".Date",date("Y/m/d"));
           $config->save();
           $event->setKeepInventory(true);
           $player->sendMessage("§6[個人通知] §7KeepInventoryを使用しました残り1回です");
        }
    }

    public function onExplosion(EntityExplodeEvent $event){
        $event->setCancelled();//Entityの爆発をキャンセル
    }

    public function onReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        
        /**
         * MenuBookをインベントリスロットの位置を固定
         * Playerがスロットを変更しようとしたらイベントキャンセル
         * クラフトできない為一旦コメントアウト
         */

        /**if($pk instanceof InventoryTransactionPacket){
            if(isset($pk->actions[0])){
                $slot = $pk->actions[0]->inventorySlot;
                if($slot === 0){
                    $event->setCancelled();
                }
            }
        */
        if ($pk instanceof InteractPacket){

            $player = $event->getPlayer();
            $eid = $pk->target;
            if($eid === null){
                return false;
            }

            if($eid === $this->main->StatusNPC){
                $this->status_text->showText($player, $this->eid);
            }
        } elseif ($pk instanceof InventoryTransactionPacket){
            $player = $event->getPlayer();
            $eid = $pk->trData->entityRuntimeId ?? null;

            if($eid === null){
                return false;
            }

            if($eid === $this->main->GovernmentNPC){
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
