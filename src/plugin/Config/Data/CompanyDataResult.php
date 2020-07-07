<?php

declare(strict_types=1);

namespace plugin\Config\Data;

use plugin\Config\ConfigBase;
use plugin\Config\ConfigList;

class CompanyDataResult
{
	private static function search(string $member, array $company_data): ?string {
		foreach($company_data as $company_name => $data) {
			if($data["owner"] === $member) return $company_name;
			if(in_array($member, $data["member"])) return $company_name;
		}

		return null;
	}

	static function getCompanyFor(string $member): ?Company {
		$config = ConfigBase::getFor(ConfigList::COMPANY);
		$data = $config->getAll(true);
		$result = self::search($member, $data);
		return $result === null ? null: new Company($result);
	}
}