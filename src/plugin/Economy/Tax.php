<?php

namespace plugin\Economy;

class Tax{

    public const CONSUMPTION_TAX = "consumption";
    public const CORPORATE_TAX = "corporate";
    public const GIFT_TAX = "gift";
    public const INCOME_TAX = "income";

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