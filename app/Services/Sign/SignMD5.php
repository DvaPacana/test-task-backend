<?php

namespace App\Services\Sign;

class SignMD5 extends AbstractSign {
	protected const DELIMETER = '.';
	protected const HASH_FUNCTION_NAME = 'md5';
}
