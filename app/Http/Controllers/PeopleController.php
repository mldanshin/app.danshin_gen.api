<?php

namespace App\Http\Controllers;

use App\Http\Requests\People\FilterOrderRequest;
use App\Models\People\FilterOrder\OrderType;
use App\Repositories\People\FilterOrder\Provider as FilterOrderProvider;
use App\Repositories\People\People;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

final class PeopleController extends Controller
{
    public function __construct(
        private readonly People $repository
    ) {}

    #[OA\Get(
        path: '/api/people',
        description: 'Список всех лиц с кратким описанием.',
        parameters: [
            new OA\Parameter(
                name: 'order',
                description: 'Ключевое слово для упорядочивания списка. '
                    ."По умолчанию 'name'",
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: [
                        OrderType::AGE,
                        OrderType::NAME,
                    ]
                )
            ),
            new OA\Parameter(
                name: 'search',
                description: 'Строка поиска.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/peoplePerson'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getAll(FilterOrderRequest $request, FilterOrderProvider $provider): JsonResponse
    {
        return response()->json(
            $this->repository->getAll(
                $provider->get($request->getPeopleRequest()->orderType),
                $request->getPeopleRequest()->search
            )
        );
    }
}
