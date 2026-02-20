<?php

namespace App\Exceptions;

use Exception;

class BaseApiException extends Exception
{
    protected int $status = 400;

    public function getStatus(): int
    {
        return $this->status;
    }
}