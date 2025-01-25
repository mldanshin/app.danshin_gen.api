<?php

namespace App\Http\Controllers\Date;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dates\DatesUpcomingRequest;
use App\Models\DateTimeCustom;
use App\Repositories\Dates\DatesAll;
use App\Repositories\Dates\DatesUpcoming;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

final class DateController extends Controller
{
    #[OA\Get(
        path: '/api/dates',
        description: 'Список всех известных дат (день рождения, день памяти). '
            .'Под известными понимаются даты, с полными данными о дне, месяце, годе. '
            .'Например дата 2000-11-1? не попадёт, из-за неизевстного дня.',
        parameters: [
            new OA\Parameter(
                name: 'sort_by',
                description: 'Параметр сортировки: "date" - по дате, "alphabet" - по алфавиту',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['date', 'alphabet'],
                    default: 'date'
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
                        ref: '#/components/schemas/datesDate'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Требуется авторизация через токен'),
        ]
    )]
    public function getAll(Request $request, DatesAll $repository): JsonResponse
    {
        $sortBy = $request->query('sort_by', config('app.dates.sort_by_default'));
        return response()->json($repository->get($sortBy));
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
                name: 'before_day',
                description: 'Количество дней до события (число от 1 до 30).',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                )
            ),
            new OA\Parameter(
                name: 'after_day',
                description: 'Количество дней после события (число от 1 до 30).',
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
                $request->before_day,
                $request->after_day
            )
        );
    }
}
