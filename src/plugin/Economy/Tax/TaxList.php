<?php

declare(strict_types=1);

namespace plugin\Economy\Tax;

class TaxList
{
	//贈与税
	const TAX_RATE_GIFT = 10;
	const TAX_MODE_GIFT = "gift";

	//所得税
	const TAX_RATE_INCOME = 10;
	const TAX_MODE_INCOME = "income";

	//法人税
	const TAX_RATE_CORPORATE = 15;
	const TAX_MODE_CORPORATE = "corporate";

	//消費税
	const TAX_RATE_CONSUMPTION = 10;
	const TAX_MODE_CONSUMPTION = "consumption";
}