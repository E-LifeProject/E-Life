<?php

declare(strict_types=1);

namespace plugin\Config;

use plugin\Main;
use pocketmine\utils\Config;

class PlayerConfigBase
{
	/** @var array **/
	private static $config = [];

	static function init(Main $main, string $name): void {
		if(!file_exists($path = self::getFolder($main, $name)))
			mkdir($path,0744,true);
		$name = self::getFolderName($name);
		self::register([
			$name => [
				"money" => 1000
			]
		], $path);
	}

	static function save(): void {
		foreach(self::$config as $key => $config) {
			$config->save();
		}
	}

	static function getFor(string $name): ?Config {
		return self::$config[$name] ?? null;
	}

	private static function isExist(string $name): bool {
		return isset(self::$config[$name]);
	}

	private static function register(array $keys, string $path): void {
		foreach($keys as $key => $default_data) {
			self::registerFor($key, $path, $default_data);
		}
	}

	private static function registerFor(string $key, string $path, array $default_data): void {
		if(self::isExist($path))
			return;

		self::$config[$key] = new Config($path.$key.".yml", Config::YAML, $default_data);
	}

	private static function getFolder(Main $main, string $name): string {
		$sub = substr($name, 0, 1);
		$upper = strtoupper($sub);
		return $main->getDataFolder().$upper.'/';
	}

	private static function getFolderName(string $name): string {
		return strtolower($name);
	}
}