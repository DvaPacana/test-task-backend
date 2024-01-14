<?php

namespace App\Services\Sign;

class SignSHA256 extends AbstractSign {
	protected const DELIMETER = ':';
	protected const HASH_FUNCTION_NAME = 'sha256';
}
