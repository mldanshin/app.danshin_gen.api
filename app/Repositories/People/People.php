<?php

namespace App\Repositories\People;

use App\Models\Eloquent\People as PeopleEloquent;
use App\Models\People\Person as PersonModel;
use App\Repositories\People\FilterOrder\FilteringOrderingContract;
use App\Repositories\People\FilterOrder\Search;
use Illuminate\Support\Collection;

final class People
{
    /**
     * @return Collection<int, PersonModel>
     */
    public function getAll(FilteringOrderingContract $filteringOrdering, ?string $search): Collection
    {
        $collect = collect();
        $filteringOrdering->select(new Search, $search)
            ->map(fn ($item) => $collect->push($this->getPerson($item)));

        return $collect;
    }

    private function getPerson(PeopleEloquent $person): PersonModel
    {
        return new PersonModel(
            $person->id,
            $person->surname,
            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy('order')->pluck('surname') : null,
            $person->name,
            $person->patronymic,
            $person->birth_date,
        );
    }
}
