<?php

namespace App\Exceptions;

class NotFoundException extends BaseApiException
{
    protected $message = 'Recurso não encontrado.';
    protected int $status = 404;
}
