<?php

namespace App\Http\Controllers\Date;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dates\DatesUpcomingRequest;
use App\Models\DateTimeCustom;
use App\Repositories\Dates\DatesAll;
use App\Repositories\Dates\DatesUpcoming;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

final class DateController extends Controller
{
    #[OA\Get(
        path: '/api/dates',
        description: 'Список всех известных дат (день рождения, день памяти). '
            .'Под известными понимаются даты, с полными данными о дне, месяце, годе. '
            .'Например дата 2000-11-1? не попадёт, из-за неизевстного дня.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/datesDate'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function getAll(DatesAll $repository): JsonResponse
    {
        return response()->json($repository->get());
    }

    #[OA\Get(
        path: '/api/dates/upcoming',
        description: 'Список ближайших дат (предыдущие дни, текущий день, '
            .'будущие дни, в соответтвии с переданными параметрами).',
        parameters: [
            new OA\Parameter(
                name: 'date',
                description: 'Текущее число в формате гггг-мм-дд',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    format: 'date'
                )
            ),
            new OA\Parameter(
                name: 'past_day',
                description: 'Количество предшествующих дней (число от 1 до 30).',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
            new OA\Parameter(
                name: 'nearest_day',
                description: 'Количество будующих дней (число от 1 до 30).',
                in: 'query',
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
                    type: 'object',
                    ref: '#/components/schemas/datesEvents'
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
            new OA\Response(response: 422, description: 'Неверные параметры запроса'),
        ]
    )]
    public function getUpcoming(DatesUpcoming $repository, DatesUpcomingRequest $request): JsonResponse
    {
        return response()->json(
            $repository->get(
                new DateTimeCustom($request->date),
                $request->past_day,
                $request->nearest_day
            )
        );
    }
}
