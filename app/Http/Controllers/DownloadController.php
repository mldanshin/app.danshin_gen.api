<?php

namespace App\Http\Controllers;

use App\Http\Requests\Download\PeopleRequest;
use App\Http\Requests\Download\PersonRequest;
use App\Http\Requests\Download\TreeRequest;
use App\Http\Validator;
use App\Repositories\Download\DataBase\CreatorFile as DataBaseCreatorFile;
use App\Repositories\Download\FileSystem;
use App\Repositories\Download\People\CreatorFile as PeopleCreatorFile;
use App\Repositories\Download\Person\CreatorFile as PersonCreatorFile;
use App\Repositories\Download\Photo\CreatorFile as PhotoCreatorFile;
use App\Repositories\Download\Tree\CreatorFile as TreeCreatorFile;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DownloadController extends Controller
{
    #[OA\Get(
        path: '/api/download/people',
        description: 'Файл-документ, содержащий всех лиц родословной.',
        parameters: [
            new OA\Parameter(
                name: 'type',
                description: 'Формат файла.',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    enum: [
                        'pdf',
                        'odt',
                    ]
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: [
                    new OA\MediaType(
                        mediaType: 'application/pdf',
                        schema: new OA\Schema(
                            type: 'string',
                            format: 'binary'
                        )
                    ),
                    new OA\MediaType(
                        mediaType: 'application/odt',
                        schema: new OA\Schema(
                            type: 'string',
                            format: 'binary'
                        )
                    ),
                ]
            ),
            new OA\Response(
                response: 302,
                description: 'Не указан формат файла в параметрах запроса.'
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Файл в переданном формате не найден (не поддерживается).'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getPeople(PeopleRequest $request): BinaryFileResponse
    {
        $creator = new PeopleCreatorFile($request->type);

        return response()->download(
            $creator->create(FileSystem::instance()->pathDirectory)
        );
    }

    #[OA\Get(
        path: '/api/download/person/{id}',
        description: 'Файл-документ, содержащий лицо с переданным id.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
            new OA\Parameter(
                name: 'type',
                description: 'Формат файла.',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    enum: [
                        'pdf',
                        'odt',
                    ]
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: [
                    new OA\MediaType(
                        mediaType: 'application/pdf',
                        schema: new OA\Schema(
                            type: 'string',
                            format: 'binary'
                        )
                    ),
                    new OA\MediaType(
                        mediaType: 'application/odt',
                        schema: new OA\Schema(
                            type: 'string',
                            format: 'binary'
                        )
                    ),
                ]
            ),
            new OA\Response(
                response: 302,
                description: 'Не указан формат файла в параметрах запроса.'
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с переданным id не найдено или
                    файл в переданном формате не поддерживается.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getPerson(string $id, PersonRequest $request): BinaryFileResponse
    {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        $creator = new PersonCreatorFile($request->type, (int) $id);

        return response()->download(
            $creator->create(FileSystem::instance()->pathDirectory)
        );
    }

    #[OA\Get(
        path: '/api/download/tree/{id}',
        description: 'Файл SVG, содержащий древо лица.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
            new OA\Parameter(
                name: 'parent_id',
                description: 'Id родителя (запрашиваемого лица) по которому строится древо, '
                    .'по умолчанию древо строится по отцу. '
                    .'Id родителя должно иметь тип integer.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\MediaType(
                    mediaType: 'application/svg',
                    schema: new OA\Schema(
                        type: 'string',
                        format: 'binary'
                    )
                )
            ),
            new OA\Response(
                response: 302,
                description: 'Параметры запроса не соответствуют ограничениям.'
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с переданным id не найдено
                    или запрошенное лицо не имеет родителя, с переданным parent_id.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getTree(string $id, TreeRequest $request): BinaryFileResponse
    {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        $creator = new TreeCreatorFile((int) $id, $request->parent_id);

        return response()->download($creator->create(FileSystem::instance()->pathDirectory));
    }

    #[OA\Get(
        path: '/api/download/db',
        description: "Файл SQL, содержащий 'dump' базу данных родословной.",
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\MediaType(
                    mediaType: 'application/sql',
                    schema: new OA\Schema(
                        type: 'string',
                        format: 'binary'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function getDataBase(): BinaryFileResponse
    {
        $creator = new DataBaseCreatorFile;

        return response()->download(
            $creator->create(FileSystem::instance()->pathDirectory)
        );
    }

    #[OA\Get(
        path: '/api/download/photo',
        description: 'Файл ZIP, содержащий все фотоснимки из родословной.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\MediaType(
                    mediaType: 'application/zip',
                    schema: new OA\Schema(
                        type: 'string',
                        format: 'binary'
                    )
                )
            ),
            new OA\Response(
                response: 204,
                description: 'Фотоснимки на сервере отсутствуют.'
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function getPhoto(): BinaryFileResponse|Response
    {
        $creator = new PhotoCreatorFile;

        return response()->download(
            $creator->create(FileSystem::instance()->pathDirectory)
        );
    }
}
