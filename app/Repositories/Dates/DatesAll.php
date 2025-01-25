<?php

namespace App\Repositories\Dates;

use App\Models\Date;
use App\Models\Dates\Date as DateModel;
use App\Models\Dates\DateType;
use App\Models\Dates\Person as PersonModel;
use App\Models\Eloquent\People as PeopleEloquent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class DatesAll
{
    /**
     * @var ?Collection<int, DateModel>
     */
    private ?Collection $dates = null;

    /**
     * @return Collection<int, DateModel>
     */
    public function get(string $sortBy): Collection
    {
        if ($this->dates === null) {
            $this->dates = collect();
            $this->initialize();
        }

        if ($sortBy === config('app.dates.sort_by.alphabet')) {
            return $this->sortByAlphabet();
        }

        return $this->sortByDate();
    }

    private function initialize(): void
    {
        $this->setBirthDate();
        $this->setDeathDate();
    }

    /**
     * @return Collection<int, DateModel>
     */
    private function sortByDate(): Collection
    {
        $sorted = $this->dates->sortBy([
            fn ($item1, $item2) => ($item1->date->string > $item2->date->string) ? true : false,
        ]);

        $newCollection = collect();
        $sorted->each(fn ($item) => $newCollection->push($item));
        return $newCollection;
    }

    /**
     * @return Collection<int, DateModel>
     */
    private function sortByAlphabet(): Collection
    {
        return $this->dates
            ->sortBy([
                'person.surname',
                'person.name', 
                'person.patronymic',
                'date.string'
            ], descending: false)
            ->values();
    }

    private function setBirthDate(): void
    {
        PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date')
            ->whereNotNull('birth_date')
            ->where('birth_date', '<>', '')
            ->whereRaw("birth_date NOT LIKE '%?%'")
            ->get()
            ->map(function ($person) {
                $this->dates->push(
                    new DateModel(
                        Date::decode($person->birth_date),
                        DateType::BIRTH,
                        new PersonModel(
                            $person->id,
                            $person->surname,
                            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy('order')->pluck('surname') : null,
                            $person->name,
                            $person->patronymic,
                        )
                    )
                );
            });
    }

    private function setDeathDate(): void
    {
        PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'death_date')
            ->whereNotNull('death_date')
            ->where('death_date', '<>', '')
            ->whereRaw("death_date NOT LIKE '%?%'")
            ->get()
            ->map(function ($person) {
                $this->dates->push(
                    new DateModel(
                        Date::decode($person->death_date),
                        DateType::DEATH,
                        new PersonModel(
                            $person->id,
                            $person->surname,
                            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy('order')->pluck('surname') : null,
                            $person->name,
                            $person->patronymic,
                        )
                    )
                );
            });
    }
}
