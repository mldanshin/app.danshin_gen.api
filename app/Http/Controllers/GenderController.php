<?php

namespace App\Http\Controllers;

use App\Repositories\Gender;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

final class GenderController extends Controller
{
    public function __construct(
        private readonly Gender $repository
    ) {}

    #[OA\Get(
        path: '/api/genders',
        description: 'Список допустимых полов лица.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'array',
                    description: 'Массив с парами ключ+значение, '
                        .'где ключ=Id, а значение=Name пола.',
                    items: new OA\Items(
                        type: 'string'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function getAll(): JsonResponse
    {
        return response()->json($this->repository->getAll());
    }
}
