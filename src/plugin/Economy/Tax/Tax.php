<?php

namespace plugin\Economy\Tax;

use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class Tax
{
	/** @var int */
    private $money;
    
    public function __construct(int $money) {
        $this->money = $money;
    }

    public function getCalculated(int $mode): int {
    	$rate = $this->getTaxRateFor($mode);
    	$fixed_rate = $rate + $this->adjustRate($mode);
    	return $this->getMoney() * ($fixed_rate / 100);
    }

    public function getTaxRateFor(int $mode): int {
    	switch($mode) {
		    case TaxList::TAX_MODE_GIFT: return TaxList::TAX_RATE_GIFT;
		    case TaxList::TAX_MODE_CONSUMPTION: return TaxList::TAX_RATE_CONSUMPTION;
		    case TaxList::TAX_MODE_CORPORATE: return TaxList::TAX_RATE_CORPORATE;
		    case TaxList::TAX_MODE_INCOME: return TaxList::TAX_RATE_INCOME;
		    default: return 0;
	    }
    }

    public function adjustRate(int $mode): float {
    	$plus = 0.0;

    	switch($mode) {
		    case TaxList::TAX_MODE_GIFT:
		    	// if($this->inRegion(1000000, 500000))
		    		// $plus = $this->getTaxRateFor($mode);
		    	if($this->inRegion(1000000, 200000))
		    		$plus = 5;
		    	if($this->inRegion(2000000, 300000))
		    		$plus = 10;
		    	if($this->inRegion(null, 3000000))
		    		$plus = 20;
		    	break;

		    case TaxList::TAX_RATE_INCOME:
		    	// if($this->inRegion(100000, 50000))
		    		// $plus = $this->getTaxRateFor($mode);
				if($this->inRegion(200000, 100000))
					$plus = 10;
				if($this->inRegion(300000, 200000))
					$plus = 20;
				if($this->inRegion(null, 300000))
					$plus = 30;
	    }

	    return $plus;
    }

    private function inRegion(int $max = null, int $min = null): bool {
    	$money = $this->getMoney();
    	if($max === null) return $money >= $min;
    	elseif($min === null) return $money <= $max;
    	else return $money <= $max && $money >= $min;
    }

	/** @return int */
	private function getMoney(): int {
		return $this->money;
	}
}