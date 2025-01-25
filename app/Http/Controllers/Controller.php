<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Api Danshin Genealogy',
    version: '1.0.0',
    description: 'Доступ к большенству данных только через API токен,
        в противном случае будет возвращаться ошибка 401.'
)]
#[OA\Components(
    schemas: [
        new OA\Schema(
            schema: 'dateInterval',
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'y',
                    description: 'Год',
                    type: 'integer'
                ),
                new OA\Property(
                    property: 'm',
                    description: 'Месяц',
                    type: 'integer'
                ),
                new OA\Property(
                    property: 'd',
                    description: 'День',
                    type: 'integer'
                ),
            ],
            required: [
                'y',
                'm',
                'd',
            ]
        ),
    ]
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
