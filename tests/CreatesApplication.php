<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * Fornece método para criar a aplicação Laravel em testes.
 */
trait CreatesApplication
{
    /**
     * Cria a aplicação para testes.
     *
     * Este método presume a estrutura padrão do Laravel.
     * Ajuste conforme necessário em seu projeto.
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
}