<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class ApplicationException extends Exception
{
    public function __construct(string $message = '', int $statusCode = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
}