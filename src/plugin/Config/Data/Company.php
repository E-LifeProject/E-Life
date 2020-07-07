<?php

declare(strict_types=1);

namespace plugin\Config\Data;

use Exception;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\utils\Config;

class Company
{
	/** @var string */
	private $name;

	/** @var Config */
	private $config;

	/**
	 * Company constructor.
	 * @param string $name
	 */
	public function __construct(string $name) {
		$this->name = $name;
		$this->config = ConfigBase::getFor(ConfigList::COMPANY);
	}

	/** @return string */
	public function getName(): string {
		return $this->name;
	}

	/** @return Config */
	private function getConfig(): Config {
		return $this->config;
	}

	public function isExist(): bool {
		return $this->getConfig()->exists($this->getName());
	}

	public function generate(): self {
		if(!$this->isExist())
			$this->getConfig()->set($this->getName(), [
				"owner" => "",
				"member" => [],
				"money" => 0,
				"industry" => "",
				"location" => ""
			]);

		return $this;
	}

	public function remove(): array {
		$data = $this->getConfig()->get($this->getName());
		$this->getConfig()->remove($this->getName());
		return $data;
	}

	public function setOwner(string $owner): self {
		$this->getConfig()->setNested($this->getName().".owner", $owner);
		return $this;
	}

	public function addMember(string $member): self {
		$implode = function(string $value, array $source): array {$source[] = $value; return $source;};
		$already = $this->getConfig()->getNested($this->getName().".member");
		in_array($member, $already) ?: $this->getConfig()->setNested(
			$this->getName().".member", $implode($member, $already)
		);

		return $this;
	}

	public function subMember(string $member): self {
		$already = $this->getConfig()->getNested($this->getName().".member");
		$diff_member = array_values(array_diff([$member], $already));
		$this->getConfig()->setNested($this->getName().".member", $diff_member);

		return $this;
	}
}