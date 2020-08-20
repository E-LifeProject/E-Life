<?php 

namespace plugin\Form;

#Basic
use \DateTime;
use pocketmine\Player;
use pocketmine\form\Form;

#E-Life
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use plugin\Economy\MoneyListener;
use plugin\Economy\Government\GovernmentMoney;
use plugin\Form\Government\Purchase;
use plugin\Form\Government\CashReceipt;
use plugin\Form\Government\GovernmentDepositBalance;
use plugin\Form\Government\GovernmentOfficial;
use plugin\Form\Government\AdministrativeManagement;


class GovernmentMenu implements Form{
    public function handleResponse(Player $player,$data):void{
        if($data === null){
            return;
        }

        switch($data){
            //政府ブロックorアイテム買取
            case 0:
                $player->sendForm(new Purchase());
            break;
            
            //保管金受け取り
            case 1:
                $instance = new MoneyListener($player->getName());
                if($instance->checkMoneyStorage()){
                    $date1 = new DateTime($instance->getMoneyStorageDate());
                    $date2 = new DateTime(date("Y/m/d"));
                    if($date1 < $date2){
                        $money = ConfigBase::getFor(ConfigList::CASH_STORAGE)->getNested($player->getName().".Money");
                        ConfigBase::getFor(ConfigList::CASH_STORAGE)->setNested($player->getName().".Money",0);
                        ConfigBase::getFor(ConfigList::CASH_STORAGE)->setNested($player->getName().".Date",0);
                        ConfigBase::getFor(ConfigList::CASH_STORAGE)->save();
                        GovernmentMoney::getInstance()->addMoney($money);
                        $player->sendMessage("§a[個人通知] §7受取可能な保管金はありません");
                    }else{
                        $player->sendForm(new CashReceipt($player->getName()));
                    }
                }else{
                    $player->sendMessage("§a[個人通知] §7受取可能な保管金はありません");
                }
            break;

            //政府の預金残高を照会（国庫)
            case 2:
                $player->sendForm(new GovernmentDepositBalance());
            break;

            //地方財政状況照会
            case 3:
                $player->sendForm(new LocalFinance());
            break;

            //権限者の確認
            case 4:
                $player->sendForm(new GovernmentOfficial());
            break;

            //市町村別の財政を管理したり、政府関係者が市町村の管理をする
            case 5:
                $player->sendForm(new AdministrativeManagement());
            break;
        }
    }


    public function jsonSerialize(){
        return[
            'type'=>'form',
            'title'=>'政府系フォーム',
            'content'=>'実行したい項目を選択してください',
            'buttons'=>[
                [
                    'text'=>'資源買取フォーム'
                ],
                [
                    'text'=>'保管金受取'
                ],
                [
                    'text'=>'政府財政状況照会'
                ],
                [
                    'text'=>'地方財政状況照会'
                ],
                [
                    'text'=>'政府関係者リスト'
                ],
                [
                    'text'=>'地方自治管理'
                ]
            ]
        ];
    }
}
?>