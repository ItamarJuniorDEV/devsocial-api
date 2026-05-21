<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchController extends Controller
{
    public function __construct(
        private readonly SearchService $search,
    ) {}

    #[OA\Get(
        path: '/api/search',
        summary: 'Buscar usuários e posts',
        security: [['sanctum' => []]],
        tags: ['Search'],
        parameters: [
            new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 1, maxLength: 100)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Resultados da busca'),
            new OA\Response(response: 422, description: 'Parâmetro q obrigatório'),
        ]
    )]
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $perPage = min(max((int) ($validated['per_page'] ?? 10), 1), 50);
        $result = $this->search->search(trim((string) $validated['q']), $perPage);

        $users = [];
        foreach ($result['users']->items() as $user) {
            $users[] = new UserResource($user);
        }

        $authId = (int) $request->user()->id;
        $posts = [];

        foreach ($result['posts']->items() as $post) {
            $post->setAttribute('mine', (int) $post->user_id === $authId);
            $post->setAttribute('liked', $post->likes()->where('user_id', $authId)->exists());
            $posts[] = new PostResource($post);
        }

        return response()->json([
            'data' => [
                'users' => $users,
                'posts' => $posts,
            ],
            'meta' => [
                'users' => [
                    'current_page' => $result['users']->currentPage(),
                    'last_page' => $result['users']->lastPage(),
                    'per_page' => $result['users']->perPage(),
                    'total' => $result['users']->total(),
                ],
                'posts' => [
                    'current_page' => $result['posts']->currentPage(),
                    'last_page' => $result['posts']->lastPage(),
                    'per_page' => $result['posts']->perPage(),
                    'total' => $result['posts']->total(),
                ],
            ],
        ]);
    }
}
