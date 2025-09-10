<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'DevSocial API',
    description: 'API RESTful da rede social para desenvolvedores',
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Local')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
)]
class OpenApi {}
