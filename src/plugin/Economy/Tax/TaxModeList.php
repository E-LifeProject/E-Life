<?php

declare(strict_types=1);

namespace plugin\Economy\Tax;

class TaxModeList
{
	//贈与税
	const TAX_GIFT = "gift";

	//所得税
	const TAX_INCOME = "income";

	//法人税
	const TAX_CORPORATE = "corporate";

	//消費税
	const TAX_CONSUMPTION = "consumption";
}