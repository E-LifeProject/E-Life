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
			ConfigList::CLUB => [],
			ConfigList::COMPANY => [],
			ConfigList::MONEY => [
				"max" => 50000,
				"min" => 0
			],
			ConfigList::TAXRATE =>[
				"consumptionTax" => 0.1,
				"corporateTax" => 0.15,
				"giftTaxMinimum" => 0.1,
				"incomeTaxMinimum" => 0.1
			],
			ConfigList::PURCHASE =>[
				"setItem" => "stone",
				"stone"=>[
					"jpnName" =>"石",
					"name" => "stone",
					"id" => 1,
					"damage"=>0,
					"price" => 1,
				],
				"oka"=>[
					"jpnName"=>"オークの原木",
					"name" => "oka",
					"id"=> 17,
					"damage"=> 0,
					"price"=> 2,
				],
				"spruce"=>[
					"jpnName"=>"マツの原木",
					"name" => "spruce",
					"id"=> 17,
					"damage" => 1,
					"price"=> 2,
				],
				"birch"=>[
					"jpnName"=>"シラカバの原木",
					"name" => "birch",
					"id"=> 17,
					"damage" => 2,
					"price"=> 2,
				],
				"jungle"=>[
					"jpnName"=>"ジャングルの原木",
					"name" => "jungle",
					"id"=> 17,
					"damage" => 2,
					"price"=> 2,
				],
				"acacia"=>[
					"jpnName"=>"アカシアの原木",
					"name" => "acacia",
					"id"=> 162,
					"damage" => 0,
					"price"=> 2,
				],
				"dark_oka"=>[
					"jpnName"=>"ダークオークの原木",
					"name" => "dark_oka",
					"id"=> 162,
					"damage" => 1,
					"price"=> 2,
				],
				"ironOre"=>[
					"jpnName"=>"鉄鉱石",
					"name" => "ironOre",
					"id"=> 15,
					"damage" => 0,
					"price"=> 5,
				]
			],
			ConfigList::GORVERNMENT => [
				"money" => 1000000000,
				"storehouse"=>[
					"stone" => 0,
					"oka" => 0,
					"spruce" => 0,
					"birch" => 0,
					"jungle" => 0,
					"acacia" => 0,
					"dark_oka" => 0,
					"ironOre" => 0,
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