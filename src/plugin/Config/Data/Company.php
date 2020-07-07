<?php

declare(strict_types=1);

namespace plugin\Config\Data;

use Exception;
use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;
use pocketmine\utils\Config;

class Company
{
	/**
	 * @param Config $company_config
	 * @param string $name
	 * @throws Exception
	 */
	public function checkCompany(Config $company_config, string $name): void {
		if(!$company_config->exists($name))
			throw new Exception("$name does not exist company.");
	}

	public function generate(string $name): bool {
		$company_config = ConfigBase::getFor(ConfigList::COMPANY);
		if($company_config->exists($name))
			return false;
		else {
			$company_config->set($name);
			return true;
		}
	}

	/**
	 * @param string $name
	 * @param string $player_name
	 * @return $this
	 * @throws Exception
	 */
	public function setOwner(string $name, string $player_name): self {
		$company_config = ConfigBase::getFor(ConfigList::COMPANY);
		$this->checkCompany($company_config, $name);

		$company_config->setNested($name.".owner", $player_name);
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $member
	 * @return $this
	 * @throws Exception
	 */
	public function setMember(string $name, string $member): self {
		$company_config = ConfigBase::getFor(ConfigList::COMPANY);
		$this->checkCompany($company_config, $name);

		$already_members = $company_config->getNested($name.".members");
		if($already_members === null)
			$company_config->setNested($name.".members", [$member]);
		else {
			$members = $already_members;
			$members[] = $member;
			$company_config->setNested($name . ".members", $members);
		}

		return $this;
	}
}