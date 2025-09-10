<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FollowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FollowController extends Controller
{
    public function __construct(
        private readonly FollowService $follow,
    ) {}

    #[OA\Post(
        path: '/api/users/{id}/follow',
        summary: 'Seguir ou deixar de seguir um usuário',
        security: [['sanctum' => []]],
        tags: ['Follow'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Estado do follow atualizado'),
            new OA\Response(response: 401, description: 'Não autenticado'),
        ]
    )]
    public function toggle(Request $request, int $id): JsonResponse
    {
        $following = $this->follow->toggle((int) $request->user()->id, $id);

        return response()->json(['data' => ['following' => $following]]);
    }
}
