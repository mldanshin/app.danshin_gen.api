<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\ParentPossibleRequest;
use App\Repositories\ParentRole;
use App\Repositories\Person\Editor\Parents;
use App\Repositories\Person\Editor\PersonShort;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

final class ParentController extends Controller
{
    #[OA\Get(
        path: '/api/parent/roles',
        description: 'Список допустимых ролей родителей.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'array',
                    description: 'Массив с парами ключ+значение, '
                        .'где ключ=id, а значение=name роли.',
                    items: new OA\Items(
                        type: 'string'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function getRoleAll(ParentRole $repository): JsonResponse
    {
        return response()->json($repository->getAll());
    }

    #[OA\Get(
        path: '/api/parent/possible',
        description: 'Список допустимых родителей для запрошенного лица.',
        parameters: [
            new OA\Parameter(
                name: 'person_id',
                description: 'Id запрашиваемого лица.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
            new OA\Parameter(
                name: 'birth_date',
                description: 'Дата рождения запрашиваемого лица.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    format: 'date',
                    description: 'Дата в формате ГГГГ-ММ-ДД, '
                        .'не должна содержать неизвестные цифры.'
                )
            ),
            new OA\Parameter(
                name: 'role_id',
                description: 'Id роли для родителя.',
                in: 'query',
                required: true,
            ),
            new OA\Parameter(
                name: 'mariages',
                description: 'Лица, претенденты на роль в браке (сожительстве).',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: 'person',
                                type: 'integer',
                                description: 'Id person должно существовать в базе данных '
                                    .'и не совпадать с запрашиваемым лицом.'
                            ),
                        ]
                    )
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
                        ref: '#/components/schemas/personEditorPersonShort'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getPossible(
        ParentPossibleRequest $request,
        Parents $repository,
        PersonShort $repositoryPersonShort
    ): JsonResponse {
        return response()->json(
            $repository->getPossiblePersonShort(
                $request->getPersonId(),
                $request->getBirthDate(),
                $request->getRoleId(),
                $request->getMariages(),
                $repositoryPersonShort
            )
        );
    }
}
