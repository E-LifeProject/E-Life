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
use plugin\Form\Government\LocalFinance;
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
                $config = ConfigBase::getFor(ConfigList::CASH_STORAGE);
                $instance = new MoneyListener($player->getName());

                /**
                 * もしも保管金受け取り日時を超えていた場合、
                 * 保管料を徴収し、一週間後に期限を延長
                 * 保管料を支払う金額がない場合は、保管金を全額徴収
                 */

                if($instance->checkMoneyStorage()){
                    $date1 = new DateTime($instance->getMoneyStorageDate());
                    $date2 = new DateTime(date("Y/m/d"));
                    if($date1 < $date2){
                        $now = $config->getNested($player->getName().".Money");
                        $money = $now - 3000;
                        if($money >= 0){
                            $config->setNested($player->getName().".Money",$money);
                            $config->setNested($player->getName().".Date",date("Y/m/d",strtotime("7 day")));
                            $config->save();
                            GovernmentMoney::getInstance()->addMoney(3000);
                            $player->sendForm(new CashReceipt($player->getName()));
                        }else{
                            $config->setNested($player->getName().".Money",0);
                            $config->setNested($player->getName().".Date",0);
                            $config->save();
                            GovernmentMoney::getInstance()->addMoney($now);
                            $player->sendMessage("§a[個人通知] §7受取可能な保管金はありません");
                        }
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