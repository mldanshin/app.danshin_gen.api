<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tree\InteractiveRequest;
use App\Http\Requests\Tree\TreeRequest;
use App\Http\Validator;
use App\Repositories\Tree\Tree as TreeRepository;
use App\View\Tree\Tree as TreeView;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use OpenApi\Attributes as OA;

final class TreeController extends Controller
{
    #[OA\Get(
        path: '/api/tree/model/{id}',
        description: 'Модель древа запрашиваемого лица.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id лица',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
            new OA\Parameter(
                name: 'parent_id',
                description: 'Id родителя (запрашиваемого лица) по которому строится древо,
                    по умолчанию древо строится случайным образом по отцу или матери.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: '',
                content: new OA\JsonContent(
                    type: 'object',
                    ref: '#/components/schemas/treeTree'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лица с переданным id не найдено.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getModel(string $id, TreeRequest $request, TreeRepository $repository): JsonResponse
    {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        $model = $repository->get((int) $id, $request->getParentId());

        return response()->json($model);
    }

    #[OA\Get(
        path: '/api/tree/image/{id}',
        description: 'Файл SVG, содержащий древо запрашиваемого лица.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
            new OA\Parameter(
                name: 'parent_id',
                description: 'Id родителя (запрашиваемого лица) по которому строится древо,
                    по умолчанию древо строится случайным образом по отцу или матери.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: [
                    new OA\MediaType(
                        mediaType: 'image/svg+xml',
                        schema: new OA\Schema(
                            type: 'string',
                        )
                    ),
                ]
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лица с переданным id не найдено.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getImage(string $id, TreeRequest $request, TreeRepository $repository): View
    {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        $model = $repository->get((int) $id, $request->getParentId());

        return (new TreeView($model, null))->view;
    }

    #[OA\Get(
        path: '/api/tree/image-interactive/{id}',
        description: 'Файл изображения, содержащий древо запрашиваемого лица, '
            .'содержащий интерактивные ссылки в древе. '
            .'Ссылка на лицо должна соответствовать шаблону path_person/person_id, '
            .'где path - путь к древу, '
            .'person_id - id номер запрашиваемого лица, '
            .'то есть Вы предоставляете path, к которому '
            .'настоящее API добавляет id лица. Ссылка на древо лица '
            .'должна соответствовать шаблону path_tree/person_id. Также Вы '
            .'должны предоставить пути к двум изображениям (image_person и image_tree), '
            .'которые будут использоваться в ссылке.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
            new OA\Parameter(
                name: 'parent_id',
                description: 'Id родителя (запрашиваемого лица) по которому строится древо,
                    по умолчанию древо строится случайным образом по отцу или матери.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
            new OA\Parameter(
                name: 'path_person',
                description: 'Путь к лицу.',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
            new OA\Parameter(
                name: 'path_tree',
                description: 'Путь к древу лица.',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
            new OA\Parameter(
                name: 'image_person',
                description: 'Путь к изображению, используемому в ссылке.',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
            new OA\Parameter(
                name: 'image_tree',
                description: 'Путь к изображению, используемому в ссылке.',
                in: 'query',
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
                        mediaType: 'image/svg+xml',
                        schema: new OA\Schema(
                            type: 'string',
                        )
                    ),
                ]
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лица с переданным id не найдено.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getImageInteractive(
        string $id,
        InteractiveRequest $request,
        TreeRepository $repository
    ): View {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        $model = $repository->get((int) $id, $request->getParentId());

        return (new TreeView($model, $request->getModel()))->view;
    }

    #[OA\Get(
        path: '/api/tree/toggle/{id}',
        description: 'Переключатель для древа. '
            .'Выбор родителя, по которому строится древо.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id запрашиваемого лица',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            ),
            new OA\Parameter(
                name: 'parent_id',
                description: 'Id родителя (запрашиваемого лица) по которому строится древо,
                    по умолчанию древо строится случайным образом по отцу или матери.
                    Id родителя должно иметь тип integer.',
                in: 'query',
                required: false,
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
                    ref: '#/components/schemas/treeToggle'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(
                response: 404,
                description: 'Лицо с запрошенным id не существует
                    или запрошенное лицо не имеет родителя, с переданным parent_id.'
            ),
            new OA\Response(response: 422, description: 'Неверные параметры запроса.'),
        ]
    )]
    public function getToggle(string $id, TreeRequest $request, TreeRepository $repository): JsonResponse
    {
        if (! Validator::requireInteger($id)) {
            abort(404);
        }

        return response()->json(
            $repository->getToggle((int) $id, $request->getParentId())
        );
    }
}
