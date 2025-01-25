<?php

namespace App\Repositories\Person\Editor;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Editor\PersonShort as PersonShortModel;

final readonly class PersonShort
{
    public function getPerson(PeopleEloquentModel $person): PersonShortModel
    {
        return new PersonShortModel(
            $person->id,
            $person->surname,
            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy('order')->pluck('surname') : null,
            $person->name,
            $person->patronymic,
            $person->birth_date,
        );
    }
}
