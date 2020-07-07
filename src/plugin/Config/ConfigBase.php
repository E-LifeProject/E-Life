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
			ConfigList::PLAYER,
			ConfigList::SHOP,
			ConfigList::CLUB,
			ConfigList::JOB_COUNT,
			ConfigList::COMPANY
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
		foreach($keys as $key) {
			self::registerFor($key, $path);
		}
	}

	private static function registerFor(string $key, string $path): void {
		if(self::isExist($key))
			return;

		self::$config[$key] = new Config($path.$key."yml", Config::YAML);
	}
}