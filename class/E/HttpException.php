<?php

namespace App\E;

use Throwable;

class HttpException extends AppException
{
	public function __construct(int $httpCode, string $message = "", $details = null, Throwable $previous = null)
	{
		parent::__construct($message, $details, $httpCode, $previous);
	}
}
