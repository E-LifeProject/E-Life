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

	//Jobの変更回数を管理
	const JOB_COUNT = "job_count";

	/*
	 * 会社の情報
	 * company_name:
	 *     owner: string
	 *     member: array
	 *     money: int
	 */
	const COMPANY = "company";
}