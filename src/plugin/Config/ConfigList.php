<?php

namespace plugin\Config;

class ConfigList
{
	//利用規約同意者はplayer.ymlにリストに記載
	const PLAYER = "player";

	//公式ショップのブロックとアイテムの価格を管理
	const SHOP = "price";

	//E-Clubの会員情報を管理
	const CLUB = "club";

	//税率を管理
	const TAXRATE = "taxRate";

	/*
	 * 会社の情報
	 * company_name:
	 *     owner: string
	 *     members: array
	 *     money: int
	 *     location: string
	 */
	const COMPANY = "company";
	
	// お金関連
	const MONEY = "money";

	//買取価格を管理
	const PURCHASE = "purchase";

	//政府の財政
	const GORVERNMENTMONEY = "governmentmoney";

}
?>