<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\PersonReaderRequest;
use App\Http\Requests\Person\PersonStoredRequest;
use App\Http\Requests\Person\PersonUpdatedRequest;
use App\Http\Validator;
use App\Models\DateTimeCustom;
use App\Repositories\Person\Editor\PersonCreated as PersonCreatedRepository;
use App\Repositories\Person\Editor\PersonDeleted as PersonDeletedRepository;
use App\Repositories\Person\Editor\PersonEditable as PersonEditableRepository;
use App\Repositories\Person\Editor\PersonStored as PersonStoredRepository;
use App\Repositories\Person\Editor\PersonUpdated as PersonUpdatedRepository;
use App\Repositories\Person\Reader\Person as PersonReaderRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

final class PersonController extends Controller
{
    #[OA\Get(
        path: '/api/person/create',
        description: 'Создание нового лица',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'object',
                    ref: '#/components/schemas/personEditorCreatedPerson'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function create(PersonCreatedRepository $repository): JsonResponse
    {
        return response()->json($repository->get());
    }

    #[OA\Post(
        path: '/api/person',
        description: 'Сохранение нового лица. '
            .'Сначала сохраните фотоснимки лица, получив имена файлов, '
            .'которые нужно передать в данному запросе.',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'is_unavailable',
                            description: 'Доступность лица (поддерживается связь, поступает о нём известна информация).',
                            type: 'boolean'
                        ),
                        new OA\Property(
                            property: 'is_live',
                            description: 'Статус живой или нет.'
                                .'Не должно противоречить полю death_date.',
                            type: 'boolean'
                        ),
                        new OA\Property(
                            property: 'gender',
                            description: 'Id пола (из допустимых значений в БД).',
                            type: 'integer'
                        ),
                        new OA\Property(
                            property: 'surname',
                            description: 'Фамилия.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'old_surname',
                            description: 'Прежние фамилии с порядковыми номерами.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'surname',
                                        description: 'Фамилия.',
                                        type: 'string',
                                    ),
                                    new OA\Property(
                                        property: 'order',
                                        description: 'Порядковый номер.',
                                        type: 'integer',
                                        minimum: 1,
                                        uniqueItems: true
                                    ),
                                ],
                                required: [
                                    'surname',
                                    'order',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'name',
                            description: 'Имя.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'patronymic',
                            description: 'Отчество. Не должно противоречить '
                                .'has_patronymic.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'has_patronymic',
                            description: 'Имеется ли отчество '
                                .'(по умолчанию отчество предполагается).',
                            type: 'boolean',
                        ),
                        new OA\Property(
                            property: 'birth_date',
                            description: 'Дата рождения в формате гггг-мм-дд, '
                                .'вместо неизвестных цифр допустим символ ?',
                            type: 'string',
                            format: 'date'
                        ),
                        new OA\Property(
                            property: 'birth_place',
                            description: 'Место рождения.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'death_date',
                            description: 'Дата смерти в формате гггг-мм-дд, '
                                .'вместо неизвестных цифр допустим символ ?',
                            type: 'string',
                            format: 'date'
                        ),
                        new OA\Property(
                            property: 'burial_place',
                            description: 'Место захоронения.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'note',
                            description: 'Примечание.',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'activities',
                            description: 'Род деятельности (работа, предпринимательство, увлечения).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        type: 'string',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                ],
                            )
                        ),
                        new OA\Property(
                            property: 'emails',
                            description: 'Адреса электронной почты.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        type: 'string',
                                        format: 'email',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                ],
                            )
                        ),
                        new OA\Property(
                            property: 'internet',
                            description: 'Интернет ресурсы (страницы в соц.сетях, личные сайты).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'name',
                                        description: 'Наименование',
                                        type: 'string',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                    new OA\Property(
                                        property: 'url',
                                        description: 'URL ссылка',
                                        type: 'string',
                                        format: 'url',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                ],
                                required: [
                                    'name',
                                    'url',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'phones',
                            description: 'Телефоны (должен состоять из 10 цифр).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        type: 'string',
                                        uniqueItems: true
                                    ),
                                ],
                            )
                        ),
                        new OA\Property(
                            property: 'residences',
                            description: 'Места проживания.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'name',
                                        description: 'Наименование',
                                        type: 'string',
                                        maxLength: 255,
                                    ),
                                    new OA\Property(
                                        property: 'date',
                                        description: 'Дата актуальности данных.'
                                            .'В формате ГГГГ-ММ-ДД, допустимо заменять на ?',
                                        type: 'string',
                                        format: 'date'
                                    ),
                                ],
                                required: [
                                    'name',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'parents',
                            description: 'Родители.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'person',
                                        description: 'Id родителя. Должен сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                    new OA\Property(
                                        property: 'role',
                                        description: 'Id роли родителя. Должна сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                ],
                                required: [
                                    'person',
                                    'role',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'marriages',
                            description: 'Брак (совместное проживание).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'role',
                                        description: 'Id роли лица. Должна сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                    new OA\Property(
                                        property: 'soulmate',
                                        description: 'Id второго лица. Должен сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                    new OA\Property(
                                        property: 'soulmate_role',
                                        description: 'Id роли второго лица. Должна сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                ],
                                required: [
                                    'role',
                                    'soulmate',
                                    'soulmate_role',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'photo',
                            description: 'Фото.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'order',
                                        description: 'Порядковый номер.',
                                        type: 'integer',
                                        minimum: 1,
                                        uniqueItems: true,
                                    ),
                                    new OA\Property(
                                        property: 'date',
                                        description: 'Дата снимка. В формате ГГГГ-ММ-ДД, возможно заменить символ на ?',
                                        type: 'string',
                                        format: 'date'
                                    ),
                                    new OA\Property(
                                        property: 'file',
                                        description: 'Файл.',
                                        type: 'string',
                                        format: 'binary'
                                    ),
                                ],
                                required: [
                                    'order',
                                    'file',
                                ]
                            )
                        ),
                    ],
                    required: [
                        'is_unavailable',
                        'is_live',
                        'gender',
                    ]
                )
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            type: 'int',
                            description: 'Id созданного лица.'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function store(
        PersonStoredRequest $request,
        PersonStoredRepository $repository
    ): JsonResponse {
        return response()->json([
            'person_id' => $repository->store($request->getModel()),
        ]);
    }

    #[OA\Get(
        path: '/api/person/{id}',
        description: "Лицо для 'чтения'",
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
            new OA\Parameter(
                name: 'date',
                description: 'Дата в формате гггг-мм-дд,
                    от которой будет идти отсчёт возраста лица и других сведений.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    format: 'date'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'object',
                    ref: '#/components/schemas/personReaderPerson'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с запрошенным id не существует.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function show(
        string $id,
        PersonReaderRequest $request,
        PersonReaderRepository $repository
    ): JsonResponse {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        return response()->json(
            $repository->getById(
                $id,
                ($request->date === null) ? new DateTimeCustom : new DateTimeCustom($request->date)
            )
        );
    }

    #[OA\Get(
        path: '/api/person/{id}/edit',
        description: "Лицо для 'изменения'",
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'object',
                    ref: '#/components/schemas/personEditorEditablePerson'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с запрошенным id не существует.'
            ),
        ]
    )]
    public function edit(PersonEditableRepository $repository, string $person): JsonResponse
    {
        if (! Validator::requireInteger($person)) {
            abort(404);
        }

        return response()->json(
            $repository->getById($person)
        );
    }

    #[OA\Put(
        path: '/api/person/{id}',
        description: 'Сохранение изменённого лица. '
            .'Сначала сохраните фотоснимки лица, получив имена файлов, '
            .'которые нужно передать в данному запросе.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'id',
                            description: 'Id лица (должно существовать в БД).',
                            type: 'integer'
                        ),
                        new OA\Property(
                            property: 'is_unavailable',
                            description: 'Доступность лица (поддерживается связь, поступает о нём известна информация).',
                            type: 'boolean'
                        ),
                        new OA\Property(
                            property: 'is_live',
                            description: 'Статус живой или нет.'
                                .'Не должно противоречить полю death_date.',
                            type: 'boolean'
                        ),
                        new OA\Property(
                            property: 'gender',
                            description: 'Id пола (из допустимых значений в БД).',
                            type: 'integer'
                        ),
                        new OA\Property(
                            property: 'surname',
                            description: 'Фамилия.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'old_surname',
                            description: 'Прежние фамилии с порядковыми номерами.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'surname',
                                        description: 'Фамилия.',
                                        type: 'string',
                                    ),
                                    new OA\Property(
                                        property: 'order',
                                        description: 'Порядковый номер.',
                                        type: 'integer',
                                        minimum: 1,
                                        uniqueItems: true
                                    ),
                                ],
                                required: [
                                    'surname',
                                    'order',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'name',
                            description: 'Имя.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'patronymic',
                            description: 'Отчество. Не должно противоречить '
                                .'has_patronymic.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'has_patronymic',
                            description: 'Имеется ли отчество '
                                .'(по умолчанию отчество предполагается).',
                            type: 'boolean',
                        ),
                        new OA\Property(
                            property: 'birth_date',
                            description: 'Дата рождения в формате гггг-мм-дд, '
                                .'вместо неизвестных цифр допустим символ ?',
                            type: 'string',
                            format: 'date'
                        ),
                        new OA\Property(
                            property: 'birth_place',
                            description: 'Место рождения.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'death_date',
                            description: 'Дата смерти в формате гггг-мм-дд, '
                                .'вместо неизвестных цифр допустим символ ?',
                            type: 'string',
                            format: 'date'
                        ),
                        new OA\Property(
                            property: 'burial_place',
                            description: 'Место захоронения.',
                            type: 'string',
                            maxLength: 255
                        ),
                        new OA\Property(
                            property: 'note',
                            description: 'Примечание.',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'activities',
                            description: 'Род деятельности (работа, предпринимательство, увлечения).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        type: 'string',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                ],
                            )
                        ),
                        new OA\Property(
                            property: 'emails',
                            description: 'Адреса электронной почты.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        type: 'string',
                                        format: 'email',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                ],
                            )
                        ),
                        new OA\Property(
                            property: 'internet',
                            description: 'Интернет ресурсы (страницы в соц.сетях, личные сайты).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'name',
                                        description: 'Наименование',
                                        type: 'string',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                    new OA\Property(
                                        property: 'url',
                                        description: 'URL ссылка',
                                        type: 'string',
                                        format: 'url',
                                        maxLength: 255,
                                        uniqueItems: true
                                    ),
                                ],
                                required: [
                                    'name',
                                    'url',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'phones',
                            description: 'Телефоны (должен состоять из 10 цифр).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        type: 'string',
                                        uniqueItems: true
                                    ),
                                ],
                            )
                        ),
                        new OA\Property(
                            property: 'residences',
                            description: 'Места проживания.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'name',
                                        description: 'Наименование',
                                        type: 'string',
                                        maxLength: 255,
                                    ),
                                    new OA\Property(
                                        property: 'date',
                                        description: 'Дата актуальности данных.'
                                            .'В формате ГГГГ-ММ-ДД, допустимо заменять на ?',
                                        type: 'string',
                                        format: 'date'
                                    ),
                                ],
                                required: [
                                    'name',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'parents',
                            description: 'Родители.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'person',
                                        description: 'Id родителя. Должен сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                    new OA\Property(
                                        property: 'role',
                                        description: 'Id роли родителя. Должна сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                ],
                                required: [
                                    'person',
                                    'role',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'marriages',
                            description: 'Брак (совместное проживание).',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'role',
                                        description: 'Id роли лица. Должна сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                    new OA\Property(
                                        property: 'soulmate',
                                        description: 'Id второго лица. Должен сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                    new OA\Property(
                                        property: 'soulmate_role',
                                        description: 'Id роли второго лица. Должна сущестовать в БД.',
                                        type: 'integer',
                                    ),
                                ],
                                required: [
                                    'role',
                                    'soulmate',
                                    'soulmate_role',
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'photo',
                            description: 'Фото.',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'order',
                                        description: 'Порядковый номер.',
                                        type: 'integer',
                                        minimum: 1,
                                        uniqueItems: true,
                                    ),
                                    new OA\Property(
                                        property: 'date',
                                        description: 'Дата снимка. В формате ГГГГ-ММ-ДД, возможно заменить символ на ?',
                                        type: 'string',
                                        format: 'date'
                                    ),
                                    new OA\Property(
                                        property: 'file',
                                        description: 'Файл.',
                                        type: 'string',
                                        format: 'binary'
                                    ),
                                ],
                                required: [
                                    'order',
                                    'file',
                                ]
                            )
                        ),
                    ],
                    required: [
                        'is_unavailable',
                        'is_live',
                        'gender',
                    ]
                )
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            type: 'int',
                            description: 'Id изменённого лица.'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с запрошенным id не существует.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function update(
        PersonUpdatedRequest $request,
        PersonUpdatedRepository $repository,
        string $person
    ): JsonResponse {
        if (! Validator::requireInteger($person)) {
            abort(404);
        }

        return response()->json([
            'person_id' => $repository->store($request->getModel()),
        ]);
    }

    #[OA\Delete(
        path: '/api/person/{person}',
        description: 'Удаление лица',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK. Пустой json.',
                content: new OA\JsonContent

            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с запрошенным id не существует.'
            ),
        ]
    )]
    public function destroy(PersonDeletedRepository $repository, string $person): JsonResponse
    {
        if (! Validator::requireInteger($person)) {
            abort(404);
        }

        $repository->delete($person);

        return response()->json();
    }
}
