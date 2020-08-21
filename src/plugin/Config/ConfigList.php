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

	//銀行口座を管理
	const BANK_ACCOUNT = "bank_account";

	//ローンを管理
	const LOAN_REVIEW = "loan_review";

	//ペナルティ管理
	const PENALTY = "penalty";

	//銀行関連
	const BANK = "bank";

	//買取価格を管理
	const PURCHASE = "purchase";

	//政府の財政
	const GORVERNMENT = "government";

	//地方の財政
	const LOCAL = "local";

	//所持金オーバーした分の現金を保管
	const CASH_STORAGE = "cash_storage";

	const CHESTLOOK = "chestlook";

}
?>