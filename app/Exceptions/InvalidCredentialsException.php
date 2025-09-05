<?php

namespace App\Exceptions;

class InvalidCredentialsException extends BaseApiException
{
    protected $message = 'Credenciais inválidas.';
    protected int $status = 401;
}
