<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Classe base para testes. Extende a TestCase padrão do Laravel.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}