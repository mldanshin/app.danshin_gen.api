<?php

namespace App\Repositories\Dates;

use App\Models\Date;
use App\Models\Dates\Date as DateModel;
use App\Models\Dates\DateType;
use App\Models\Dates\Person as PersonModel;
use App\Models\Eloquent\People as PeopleEloquent;
use Illuminate\Support\Collection;

final class DatesAll
{
    /**
     * @var ?Collection<int, DateModel>
     */
    private ?Collection $dates = null;

    /**
     * @return Collection<int, DateModel>
     */
    public function get(): Collection
    {
        if ($this->dates === null) {
            $this->dates = collect();
            $this->initialize();
        }

        return $this->dates;
    }

    private function initialize(): void
    {
        $this->setBirthDate();
        $this->setDeathDate();
        $sorted = $this->dates->sortBy([
            fn ($item1, $item2) => ($item1->date->string > $item2->date->string) ? true : false,
        ]);

        $newCollection = collect();
        $sorted->each(fn ($item) => $newCollection->push($item));
        $this->dates = $newCollection;
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
                        DateType::Birth,
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
                        DateType::Death,
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
