<?php

namespace App\Models\Download\People;

use App\Models\Date;
use Illuminate\Support\Collection;

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
        public int $id,
        public bool $isUnavailable,
        public string $gender,
        public ?string $surname,
        public ?Collection $oldSurname,
        public ?string $name,
        public ?string $patronymic,
        public ?Date $birthDate,
        public ?string $birthPlace,
        public ?Date $deathDate,
        public ?string $burialPlace,
        public ?string $note,
        public ?Collection $activities,
        public ?Collection $emails,
        public ?Collection $internet,
        public ?Collection $phones,
        public ?Collection $residences,
        public ?Collection $parents,
        public ?Collection $marriages,
        public ?Collection $children,
        public ?Collection $brothersSisters,
        public ?Collection $photo
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
