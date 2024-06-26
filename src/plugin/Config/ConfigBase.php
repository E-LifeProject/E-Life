<?php

declare(strict_types=1);

namespace plugin\Config;

use plugin\Main;
use pocketmine\utils\Config;

class ConfigBase
{
	/** @var array */
	private static $config = [];

	static function init(Main $main): void {
		if(!file_exists($path = $main->getDataFolder()))
			mkdir($path,0744,true);
		self::register([
			ConfigList::PLAYER => [],
			ConfigList::KEEP_INVENTORY =>[],
			ConfigList::CLUB => [],
			ConfigList::COMPANY => [],
			ConfigList::TIME =>[],
			ConfigList::BANK_ACCOUNT => [],
			ConfigList::BANK => [
				"money"=> 10000000,//1000万
			],
			ConfigList::LOAN_REVIEW => [],
			ConfigList::LOAN_PENALTY => [],
			ConfigList::PUNISHMENT => [],
			ConfigList::RELIABILITY => [],
			ConfigList::PUNISHMENT_LOG => [],
			ConfigList::XUID => [],
			ConfigList::CHESTLOOK => [],
			ConfigList::CASH_STORAGE => [],
			ConfigList::CHATCOUNT => [],
			ConfigList::TAXRATE =>[
				"consumptionTax" => 0.1,
				"corporateTax" => 0.15,
				"giftTaxMinimum" => 0.1,
				"incomeTaxMinimum" => 0.1
			],
			ConfigList::PURCHASE =>[
				"setType" => "type1",
				"type1" => [
					"stone",
					"birch",
					"dark_oka",
					"ironOre"
				],
				"type2" => [
					"oka",
					"jungle",
					"acacia",
					"coal"
				],
				"type3" => [
					"spruce",
					"acacia",
					"glass",
					"concrete"
				]
			],
			ConfigList::ITEM_DATA => [
				"stone"=>[
					"jpnName" =>"石",
					"name" => "stone",
					"id" => 1,
					"damage"=>0,
					"price" => [
						"purchase" => 10,
						"selling" => 15
					]
				],
				"concrete"=>[
					"jpnName"=>"コンクリート",
					"name"=>"concrete",
					"id"=>236,
					"damege"=>0,
					"price"=> [
						"purchase" => 10,
						"selling" => 15
					]
				],
				"glass"=>[
					"jpnName"=>"ガラス",
					"name"=>"glass",
					"id"=> 20,
					"damage"=> 0,
					"price"=> [
						"purchase" => 10,
						"selling" => 15, 
					]
				],
				"oka"=>[
					"jpnName"=>"オークの原木",
					"name" => "oka",
					"id"=> 17,
					"damage"=> 0,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"birch"=>[
					"jpnName"=>"シラカバの原木",
					"name" => "birch",
					"id"=> 17,
					"damage" => 2,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"dark_oka"=>[
					"jpnName"=>"ダークオークの原木",
					"name" => "dark_oka",
					"id"=> 162,
					"damage" => 1,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"jungle"=>[
					"jpnName"=>"ジャングルの原木",
					"name" => "jungle",
					"id"=> 17,
					"damage" => 2,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"acacia"=>[
					"jpnName"=>"アカシアの原木",
					"name" => "acacia",
					"id"=> 162,
					"damage" => 0,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"spruce"=>[
					"jpnName"=>"マツの原木",
					"name" => "spruce",
					"id"=> 17,
					"damage" => 1,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"acacia"=>[
					"jpnName"=>"アカシアの原木",
					"name" => "acacia",
					"id"=> 162,
					"damage" => 0,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				],
				"coal" => [
					"jpnName"=>"石炭",
					"name"=>"coal",
					"id"=> 263,
					"damege" => 0,
					"price" => [
						"purchase" => 3,
						"selling" => 5
					]
				],
				"ironOre"=>[
					"jpnName"=>"鉄鉱石",
					"name" => "ironOre",
					"id"=> 15,
					"damage" => 0,
					"price"=> [
						"purchase" => 4,
						"selling" => 8
					]
				]
			],
			ConfigList::GORVERNMENT => [
				"money" => 100000000,//1億
				"storehouse"=>[
					"stone" => 0,
					"oka" => 0,
					"spruce" => 0,
					"birch" => 0,
					"jungle" => 0,
					"acacia" => 0,
					"dark_oka" => 0,
					"ironOre" => 0,
					"concrete" => 0,
					"glass" => 0,
					"coal" => 0,
				]
			],
			ConfigList::LOCAL =>[
				"city1" => [
					"money" =>0
					]
			]
		], $path);
	}

	static function save(): void {
		foreach(self::$config as $key => $config) {
			$config->save();
		}
	}

	static function getFor(string $type): ?Config {
		return self::$config[$type] ?? null;
	}

	private static function isExist(string $key): bool {
		return isset(self::$config[$key]);
	}

	private static function register(array $keys, string $path): void {
		foreach($keys as $key => $default_data) {
			self::registerFor($key, $default_data, $path);
		}
	}

	private static function registerFor(string $key, array $default_data, string $path): void {
		if(self::isExist($key))
			return;

		self::$config[$key] = new Config($path.$key.".yml", Config::YAML, $default_data);
	}
}