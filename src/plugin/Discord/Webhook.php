<?php

declare(strict_types=1);

namespace plugin\Discord;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class Webhook
{
	const LEAD_URL = "https://discordapp.com/api/webhooks/";

	static function sendMessage(string $message, string $channelType = ChannelList::SERVER): void {
		if(($webHookURL = self::getWebhook($channelType)) === ChannelList::NONE)
			return;

		// $message = preg_replace('/`/', '\`', $message);
		$message = preg_replace("/@/", "＠", $message);
		$message = preg_replace("/§[1-9a-z]/i", "", $message);
		$option = serialize(['content' => $message, 'username' => 'Server']);
		Server::getInstance()->getAsyncPool()->submitTask(new Notification($webHookURL, $option));
	}

	private static function getWebhook(string $channelType): string {
		$data = json_decode(file_get_contents(__DIR__."webhook_data.json"), true);
		return self::LEAD_URL.$data["url"][$channelType] ?? ChannelList::NONE;
	}
}

class Notification extends AsyncTask
{
	/** @var string */
	private $webHookURL;

	/** @var string */
	private $curlOptions;

	public function __construct(string $webHookURL, string $curlOptions) {
		$this->webHookURL = $webHookURL;
		$this->curlOptions = $curlOptions;
	}

	public function onRun() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->webHookURL);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(unserialize($this->curlOptions)));
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_exec($curl);
	}
}