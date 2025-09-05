<?php

namespace App\Exceptions;

class UnauthorizedException extends BaseApiException
{
    protected $message = 'Não autorizado.';
    protected int $status = 403;
}
