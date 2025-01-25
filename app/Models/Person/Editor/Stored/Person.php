<?php

namespace App\Models\Person\Editor\Stored;

use App\Models\Date;
use Illuminate\Support\Collection;

final readonly class Person
{
    /**
     * @param  Collection<int, OldSurname>  $oldSurname
     * @param  Collection<int, string>  $activities
     * @param  Collection<int, string>  $emails
     * @param  Collection<int, Internet>  $internet
     * @param  Collection<int, string>  $phones
     * @param  Collection<int, Residence>  $residences
     * @param  Collection<int, ParentModel>  $parents
     * @param  Collection<int, Marriage>  $marriages
     * @param  Collection<int, Photo>  $photo
     */
    public function __construct(
        public bool $isUnavailable,
        public bool $isLive,
        public int $gender,
        public ?string $surname,
        public Collection $oldSurname,
        public ?string $name,
        public ?string $patronymic,
        public bool $hasPatronymic,
        public ?Date $birthDate,
        public ?string $birthPlace,
        public ?Date $deathDate,
        public ?string $burialPlace,
        public ?string $note,
        public Collection $activities,
        public Collection $emails,
        public Collection $internet,
        public Collection $phones,
        public Collection $residences,
        public Collection $parents,
        public Collection $marriages,
        public Collection $photo
    ) {}
}
