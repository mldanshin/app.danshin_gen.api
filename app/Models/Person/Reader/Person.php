<?php

namespace App\Models\Person\Reader;

use App\Models\Date;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderPerson',
    title: "Лицо для 'чтения'.",
    required: [
        'id',
        'isUnavailable',
        'gender',
    ]
)]
final readonly class Person
{
    public bool $isLive;

    /**
     * @param  Collection<int, string>|null  $oldSurname
     * @param  Collection<int, string>|null  $activities
     * @param  Collection<int, string>|null  $emails
     * @param  Collection<int, Internet>|null  $internet
     * @param  Collection<int, string>|null  $phones
     * @param  Collection<int, Residence>|null  $residences
     * @param  Collection<int, ParentModel>|null  $parents
     * @param  Collection<int, Marriage>|null  $marriages
     * @param  Collection<int, PersonShort>|null  $children
     * @param  Collection<int, PersonShort>|null  $brothersSisters
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
                type: 'string'
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
                ref: '#/components/schemas/personReaderInternet'
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
                ref: '#/components/schemas/personReaderResidence'
            )
        )]
        public ?Collection $residences,

        #[OA\Property(
            description: 'Родители.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personReaderParentModel'
            )
        )]
        public ?Collection $parents,

        #[OA\Property(
            description: 'Брак (совместное проживание).',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personReaderMarriage'
            )
        )]
        public ?Collection $marriages,

        #[OA\Property(
            description: 'Дети.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personReaderPersonShort'
            )
        )]
        public ?Collection $children,

        #[OA\Property(
            description: 'Братья и сёстра.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personReaderPersonShort'
            )
        )]
        public ?Collection $brothersSisters,

        #[OA\Property(
            description: 'Фото.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/personReaderPhoto'
            )
        )]
        public ?Collection $photo,

        #[OA\Property(
            description: 'Возраст (объект DateInterval).',
            ref: '#/components/schemas/dateInterval'
        )]
        public ?\DateInterval $age,

        #[OA\Property(
            description: 'Интервал с даты смерти (объект DateInterval).',
            ref: '#/components/schemas/dateInterval'
        )]
        public ?\DateInterval $deathDateInterval
    ) {
        $this->setIsLive();
    }

    private function setIsLive(): void
    {
        if ($this->deathDate === null) {
            $this->isLive = true;
        } else {
            $this->isLive = false;
        }
    }
}
