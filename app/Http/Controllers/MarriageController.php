<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\MarriagePossibleRequest;
use App\Http\Validator;
use App\Repositories\MarriageRole;
use App\Repositories\Person\Editor\Marriage;
use App\Repositories\Person\Editor\PersonShort;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

final class MarriageController extends Controller
{
    #[OA\Get(
        path: '/api/marriage/roles',
        description: 'Список допустимых ролей в браке '
            .'(к браку приравнено совместное проживание).',
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
    public function getRoleAll(MarriageRole $repository): JsonResponse
    {
        return response()->json($repository->getAll());
    }

    #[OA\Get(
        path: '/api/marriage/roles/{gender}',
        description: 'Список допустимых ролей (выборка по полу) в браке '
            .'(к браку приравнено совместное проживание).',
        parameters: [
            new OA\Parameter(
                name: 'gender',
                description: 'Id gender.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
        ],
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
            new OA\Response(
                response: 404,
                description: 'Id запрошенного пола не найдено.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getRoleByGender(MarriageRole $repository, string $gender): JsonResponse
    {
        if (! Validator::requireInteger($gender)) {
            abort(404);
        }

        return response()->json(
            $repository->getByGender($gender)
        );
    }

    #[OA\Get(
        path: '/api/marriage/possible',
        description: 'Список допустимых лиц для брака (совместного проживания) '
            .'запрашиваемого лица.',
        parameters: [
            new OA\Parameter(
                name: 'person_id',
                description: 'Id лица (должно существовать в базе данных).',
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
                description: 'Id роли в браке запрашиваемого лица.',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
            new OA\Parameter(
                name: 'parents',
                description: 'Лица, претенденты на роль родителей.',
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
                    type: 'object',
                    ref: '#/components/schemas/personEditorMarriagePossible'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getPossible(
        MarriagePossibleRequest $request,
        Marriage $repository,
        PersonShort $repositoryPersonShort
    ): JsonResponse {
        return response()->json(
            $repository->getPossiblePeople(
                $request->getPersonId(),
                $request->getBirthDate(),
                $request->getRoleId(),
                $request->getParents(),
                $repositoryPersonShort
            )
        );
    }
}
