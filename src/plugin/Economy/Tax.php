<?php

namespace plugin\Economy;

use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class Tax{

    public const CONSUMPTION_TAX = "consumption"; //消費税
    public const CORPORATE_TAX = "corporate"; //法人税
    public const GIFT_TAX = "gift"; //贈与税
    public const INCOME_TAX = "income"; //所得税

    private $money;
    
    public function __construct(int $money){
        $this->money = $money;
        $taxRate = ConfigBase::getFor(ConfigList::TAXRATE);
    }

    /**
     * 税金を計算して返す
     * 税金を含めた合計金額を返すわけではないので注意
     */

    public function TaxCalculate(int $mode): int{
        switch($mode){
            case Tax::CONSUMPTION_TAX:
                $tax = $this->money * $consumptionTaxRate;
                return $tax;
            break;

            case Tax::CORPORATE_TAX:
                $tax = $this->money * $corporateTaxRate;
                return $tax;
            break;

            case Tax::GIFT_TAX:

                if(1000000 >= $this->money && $this->money >= 500000){
                    $tax = $this->money * $giftTaxRate;
                }elseif(200000 >= $this->money && $this->money >1000000){
                    $giftTaxRate += 0.05;
                    $tax = $this->money * $giftTaxRate;
                }elseif(300000 >= $this->money && $this->money >2000000){
                    $giftTaxRate += 0.1;
                    $tax = $this->money * $giftTaxRate;
                }elseif($this->money > 3000000){
                    $giftTaxRate += 0.2;
                    $tax = $this->money * $giftTaxRate;
                }else{
                    $tax = 0;
                }
                return $tax;

            break;

            case Tax::INCOME_TAX:
                if(100000 >= $this->money && $this->money >= 50000){
                    $tax = $this->money * $incomeTaxRate;
                }elseif(200000 >= $this->money && $this->money > 100000){
                    $incomeTaxRate += 0.1;
                    $tax = $this->money * $incomeTaxRate;
                }elseif(300000 >= $this->money && $this->money > 200000){
                    $incomeTaxRate += 0.2;
                    $tax = $this->money * $incomeTaxRate;
                }elseif($this->money > 300000){
                    $incomeTaxRate += 0.3;
                    $tax = $this->money * $incomeTaxRate;
                }else{
                    $tax = 0;
                }
                return $tax;
            break;
        }
    }
}

?>