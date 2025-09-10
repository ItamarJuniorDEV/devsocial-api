<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\FeedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FeedController extends Controller
{
    public function __construct(
        private readonly FeedService $feed,
    ) {}

    #[OA\Get(
        path: '/api/feed',
        summary: 'Listar feed do usuário autenticado',
        security: [['sanctum' => []]],
        tags: ['Feed'],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de posts'),
            new OA\Response(response: 401, description: 'Não autenticado'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);
        $paginator = $this->feed->feed((int) $request->user()->id, $perPage);

        $posts = [];
        foreach ($paginator->items() as $post) {
            $posts[] = new PostResource($post);
        }

        return response()->json([
            'data' => $posts,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
