<?php

namespace App\Contracts;

interface SignInterface {

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function sign(array $data): string;

}
