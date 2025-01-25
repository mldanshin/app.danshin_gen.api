<?php

namespace App\Models\Person\Editor\Editable;

use App\Models\Date;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorEditablePerson',
    title: 'Лицо для редактирования.',
    required: [
        'id',
        'isUnavailable',
        'isLive',
        'gender',
        'hasPatronymic',
    ]
)]
final readonly class Person
{
    /**
     * @param  Collection<int, OldSurname>|null  $oldSurname
     * @param  Collection<int, string>|null  $activities
     * @param  Collection<int, string>|null  $emails
     * @param  Collection<int, Internet>|null  $internet
     * @param  Collection<int, string>|null  $phones
     * @param  Collection<int, Residence>|null  $residences
     * @param  Collection<int, ParentModel>|null  $parents
     * @param  Collection<int, Marriage>|null  $marriages
     * @param  Collection<int, Photo>|null  $photo
     */
    public function __construct(
        #[OA\Property(
            description: 'Id.',
        )]
        public int $id,

        #[OA\Property(
            description: 'Доступное ли лицо для связи.',
        )]
        public bool $isUnavailable,

        #[OA\Property(
            description: 'Является лицо живым или нет.',
        )]
        public bool $isLive,

        #[OA\Property(
            description: 'Пол.',
        )]
        public int $gender,

        #[OA\Property(
            description: 'Фамилия.',
        )]
        public ?string $surname,

        #[OA\Property(
            description: 'Прежние фамилии.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personEditorEditableOldSurname'
            )
        )]
        public ?Collection $oldSurname,

        #[OA\Property(
            description: 'Имя.',
        )]
        public ?string $name,

        #[OA\Property(
            description: 'Отчество.',
        )]
        public ?string $patronymic,

        #[OA\Property(
            description: 'Дата рождения.',
            ref: '#/components/schemas/date'
        )]
        public ?Date $birthDate,

        #[OA\Property(
            description: 'Место рождения.',
        )]
        public ?string $birthPlace,

        #[OA\Property(
            description: 'Дата смерти.',
            ref: '#/components/schemas/date'
        )]
        public ?Date $deathDate,

        #[OA\Property(
            description: 'Место захоронения.',
        )]
        public ?string $burialPlace,

        #[OA\Property(
            description: 'Примечание.',
        )]
        public ?string $note,

        #[OA\Property(
            description: 'Виды деятельности.',
            type: 'array',
            items: new OA\Items(
                type: 'string'
            )
        )]
        public ?Collection $activities,

        #[OA\Property(
            description: 'Электронная почта.',
            type: 'array',
            items: new OA\Items(
                type: 'string'
            )
        )]
        public ?Collection $emails,

        #[OA\Property(
            description: 'Интернет ресурсы.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personEditorEditableInternet'
            )
        )]
        public ?Collection $internet,

        #[OA\Property(
            description: 'Телефоны.',
            type: 'array',
            items: new OA\Items(
                type: 'string'
            )
        )]
        public ?Collection $phones,

        #[OA\Property(
            description: 'Места проживания.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personEditorEditableResidence'
            )
        )]
        public ?Collection $residences,

        #[OA\Property(
            description: 'Родители.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personEditorEditableParentModel'
            )
        )]
        public ?Collection $parents,

        #[OA\Property(
            description: 'Брак (совместное проживание).',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personEditorEditableMarriage'
            )
        )]
        public ?Collection $marriages,

        #[OA\Property(
            description: 'Фото.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personEditorEditablePhoto'
            )
        )]
        public ?Collection $photo,

        #[OA\Property(
            description: 'Имеется ли у лица отчество.',
        )]
        public bool $hasPatronymic
    ) {}
}
