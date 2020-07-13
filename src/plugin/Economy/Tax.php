<?php

namespace plugin\Economy;

#Basic





class Tax{

    private $money;
    
    public function __construct(int $money){
        $this->money = $money;
    }

    public function TaxCalculate(int $mode): int{
        //場合わけすればいいん？
        $totalMoney = $this->money * $consumptionTax;
        return $totalMoney;
    }
}

?>