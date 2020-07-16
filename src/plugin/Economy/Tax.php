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
    }

    /**
     * 税金を計算して返す
     * 税金を含めた合計金額を返すわけではないので注意
     */

    public function TaxCalculate(int $mode): int{
        if(TaxList::CONSUMPTION_TAX === $mode){
            $tax = $this->money * $consumptionTax;
        }elseif(TaxList::CORPORATE_TAX === $mode){
            $tax = $this->money * $corporateTax;
        }elseif(TaxList::GIFT_TAX === $mode){
            $tax = $this->money * $giftTax;
        }elseif(TaxList::INCOME_TAX === $mode){
            $tax = $this->money * $incomeTax;
        }
        return $tax;
    }
}

?>