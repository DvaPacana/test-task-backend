<?php

namespace App\Services\Sign;

use App\Contracts\SignInterface;

abstract class AbstractSign implements SignInterface {
	protected const DELIMETER = '';
	protected const HASH_ALGO_NAME = '';

	protected string $appKey;

	public function __construct($appKey) {
		$this->appKey = $appKey;
	}

	public function sign(array $data): string
	{
		$dataClone = $data;

		unset($dataClone['sing']);
		ksort($dataClone);
		
		$dataString = implode(static::DELIMETER, $data) . $this->appKey;

		return hash(static::HASH_ALGO_NAME, $dataString);
	}

}
