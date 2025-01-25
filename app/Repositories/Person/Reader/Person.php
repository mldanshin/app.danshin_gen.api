<?php

namespace App\Repositories\Person\Reader;

use App\Exceptions\DataNotFoundException;
use App\Models\CalculatorDateInterval;
use App\Models\Date;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Reader\Internet as InternetModel;
use App\Models\Person\Reader\Marriage as MarriageModel;
use App\Models\Person\Reader\ParentModel;
use App\Models\Person\Reader\Person as PersonModel;
use App\Models\Person\Reader\PersonShort as PersonShortModel;
use App\Models\Person\Reader\Photo as PhotoModel;
use App\Models\Person\Reader\Residence as ResidenceModel;
use App\Services\Photo\FileSystem as PhotoFileSystem;
use Illuminate\Support\Collection;

final class Person
{
    public function __construct(private readonly PhotoFileSystem $photoFileSystem) {}

    /**
     * @throws DataNotFoundException
     */
    public function getById(int $id, \DateTime $today): PersonModel
    {
        $person = PeopleEloquentModel::find($id);

        if ($person === null) {
            throw new DataNotFoundException('Requested person does not exist.');
        }

        $birthDate = Date::decode($person->birth_date);
        $deathDate = Date::decode($person->death_date);
        $calculatorDateInterval = new CalculatorDateInterval($today, $birthDate, $deathDate);

        return new PersonModel(
            $person->id,
            $person->is_unavailable,
            $person->gender_id,
            $person->surname,
            ($person->oldSurname->count() > 0) ? $person->oldSurname()->orderBy('order')->pluck('surname') : null,
            $person->name,
            $person->patronymic,
            $birthDate,
            $person->birth_place,
            $deathDate,
            $person->burial_place,
            $person->note,
            ($person->activities()->count() > 0) ? collect($person->activities()->pluck('name')->all()) : null,
            ($person->emails()->count() > 0) ? collect($person->emails()->pluck('name')->all()) : null,
            $this->getInternet($person),
            ($person->phones()->count() > 0) ? collect($person->phones()->pluck('name')->all()) : null,
            $this->getResidences($person),
            $this->getParents($person),
            $this->getMarriages($person->id),
            $this->getChildrens($person),
            $this->getBrothersSisters($person->id),
            $this->getPhoto($person),
            $calculatorDateInterval->age,
            $calculatorDateInterval->intervalDeath
        );
    }

    /**
     * @return Collection<int, InternetModel>|null
     */
    private function getInternet(PeopleEloquentModel $person): ?Collection
    {
        $array = [];
        $collection = $person->internet()->get();
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $array[] = new InternetModel($item->url, $item->name);
            }

            return collect($array);
        } else {
            return null;
        }
    }

    /**
     * @return Collection<int, ResidenceModel>|null
     */
    private function getResidences(PeopleEloquentModel $person): ?Collection
    {
        $array = [];
        $collection = $person->residences()->get();
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $array[] = new ResidenceModel($item->name, Date::decode($item->date));
            }

            return collect($array);
        } else {
            return null;
        }
    }

    /**
     * @return Collection<int, ParentModel>|null
     */
    private function getParents(PeopleEloquentModel $person): ?Collection
    {
        $array = [];

        $collection = $person->parents()->get();
        $collection->each(
            function ($item) use (&$array) {
                $array[] = new ParentModel(
                    $this->getPersonById($item->parent_id),
                    $item->parent_role_id
                );
            }
        );

        return empty($array) ? null : collect($array);
    }

    /**
     * @return Collection<int, MarriageModel>|null
     */
    private function getMarriages(int $personId): ?Collection
    {
        $array = [];

        $roleScope = MarriageRoleScopeEloquentModel::get();

        $collection1 = MarriageEloquentModel::where('person1_id', $personId)->get();
        $collection1->each(
            function ($item) use (&$array, $roleScope) {
                $array[] = $this->getMarriage(
                    $item->person2_id,
                    $roleScope->find($item->role_scope_id)->role2_id
                );
            }
        );

        $collection2 = MarriageEloquentModel::where('person2_id', $personId)->get();
        $collection2->each(
            function ($item) use (&$array, $roleScope) {
                $array[] = $this->getMarriage(
                    $item->person1_id,
                    $roleScope->find($item->role_scope_id)->role1_id
                );
            }
        );

        return empty($array) ? null : collect($array);
    }

    private function getMarriage(int $person, int $type): MarriageModel
    {
        return new MarriageModel(
            $this->getPersonById($person),
            $type
        );
    }

    /**
     * @return Collection<int, PersonShortModel>|null
     */
    private function getChildrens(PeopleEloquentModel $person): ?Collection
    {
        $childrens = $person->childrens()->pluck('child_id')->all();

        return empty($childrens) ? null : $this->getPeopleShortById($childrens);
    }

    /**
     * @return Collection<int, PersonShortModel>|null
     */
    private function getBrothersSisters(int $personId): ?Collection
    {
        $array = [];

        $funcParent = function ($query) use ($personId) {
            $query->select('parent_id')->from('parent_child')->where('child_id', $personId);
        };

        $array = ParentChildEloquentModel::whereIn('parent_id', $funcParent)
            ->where('child_id', '<>', $personId)
            ->pluck('child_id')
            ->all();

        return empty($array) ? null : $this->getPeopleShortById($array);
    }

    /**
     * @param  array|int[]  $id
     * @return Collection|PersonShortModel[]
     */
    private function getPeopleShortById(array $id): Collection
    {
        $people = PeopleEloquentModel::whereIn('id', $id)->orderBy('birth_date')->get();

        $array = [];
        foreach ($people as $person) {
            $array[] = $this->getPerson($person);
        }

        return collect($array);
    }

    private function getPersonById(int $id): PersonShortModel
    {
        $person = PeopleEloquentModel::find($id);

        return $this->getPerson($person);
    }

    private function getPerson(PeopleEloquentModel $person): PersonShortModel
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

    /**
     * @return Collection<int, PhotoModel>|null
     */
    private function getPhoto(PeopleEloquentModel $person): ?Collection
    {
        $collect = $person->photo()->orderBy('order')->get()
            ->map(
                function ($item) {
                    return new PhotoModel(
                        $item->order,
                        $item->file,
                        Date::decode($item->date)
                    );
                }
            );

        if ($collect->count() > 0) {
            return $collect;
        } else {
            return null;
        }
    }
}
