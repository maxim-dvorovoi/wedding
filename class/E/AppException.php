<?php

namespace App\E;

use Exception;
use Throwable;

class AppException extends Exception
{
	private $details;

	public function __construct(string $message = "", $details = null, int $code = 0, Throwable $previous = null)
	{
		$this->details = $details;
		parent::__construct($message, $code, $previous);
	}

	public function getDetails()
	{
		return $this->details;
	}
}
