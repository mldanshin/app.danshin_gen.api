<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\PhotoStoredRequest;
use App\Http\Validator;
use App\Repositories\Person\Editor\Photo as PhotoEditorRepository;
use App\Repositories\Person\Reader\Photo as PhotoRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class PhotoController extends Controller
{
    public function __construct(private PhotoRepository $repository) {}

    #[OA\Get(
        path: '/api/person-photo/{personId}',
        description: 'Массив описаний фотоснимков для лица (!!!не файлы).',
        parameters: [
            new OA\Parameter(
                name: 'personId',
                description: 'Id лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
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
                        ref: '#/components/schemas/personReaderPhoto'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лица с переданным id не найдено.'
            ),
        ]
    )]
    public function getListByPerson(string $personId): JsonResponse
    {
        if (! Validator::requireInteger($personId)) {
            abort(404);
        }

        return response()->json(
            $this->repository->getListByPerson($personId)
        );
    }

    #[OA\Get(
        path: '/api/photo/{personId}/{fileName}',
        description: 'Файл фотографии (бинарный).',
        parameters: [
            new OA\Parameter(
                name: 'personId',
                description: 'Id лица.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
            new OA\Parameter(
                name: 'fileName',
                description: 'Имя файла фото.',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: [
                    new OA\MediaType(
                        mediaType: 'image/jpg',
                        schema: new OA\Schema(
                            type: 'string',
                            format: 'binary'
                        )
                    ),
                ]
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Фото с переданными параметрами не найдено.'
            ),
        ]
    )]
    public function show(string $personId, string $fileName): BinaryFileResponse
    {
        if (! Validator::requireInteger($personId)) {
            abort(404);
        }

        return response()->download(
            $this->repository->getPath((int) $personId, $fileName)
        );
    }

    #[OA\Post(
        path: '/api/photo/temp',
        description: 'Сохранить файл фотоснимка во временное хранилище',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK. ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            type: 'string',
                            description: 'Имя файла в поле fileName'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function storeTemp(
        PhotoStoredRequest $request,
        PhotoEditorRepository $repository
    ): JsonResponse {
        return response()->json([
            'fileName' => $repository->storeTemp($request->getFile()),
        ]);
    }
}
