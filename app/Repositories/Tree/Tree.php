<?php

namespace App\Repositories\Tree;

use App\Exceptions\DataNotFoundException;
use App\Models\Date;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use App\Models\Tree\PersonShort as PersonShortModel;
use App\Models\Tree\Toggle as ToggleModel;
use App\Models\Tree\Tree as TreeModel;
use App\Repositories\Validator;
use Illuminate\Support\Collection;

final class Tree
{
    /**
     * @throws DataNotFoundException
     */
    public function get(int $personId, ?int $parentId): TreeModel
    {
        Validator::checkPerson($personId);

        if ($parentId === null) {
            $parentId = $this->getParentId($personId);
        } else {
            Validator::checkParent($personId, $parentId);
        }

        $personRootFamily = $this->getPersonRootFamily($personId, $parentId);

        return new TreeModel(
            $this->getPersonShort($personId),
            $this->getFamily($personRootFamily, $personId)
        );
    }

    /**
     * @throws DataNotFoundException
     */
    public function getToggle(int $personId, ?int $parentId): ToggleModel
    {
        Validator::checkPerson($personId);

        Validator::checkParent($personId, $parentId);

        if ($parentId === null) {
            $parentId = $this->getParentId($personId);
        }

        if ($parentId !== null) {
            return new ToggleModel(
                $this->getPersonShort($personId),
                $this->getParentsToggle($personId),
                $parentId
            );
        } else {
            return new ToggleModel(
                $this->getPersonShort($personId),
                collect(),
                null
            );
        }
    }

    private function getPersonRootFamily(int $personId, ?int $parentId): int
    {
        if ($parentId === null) {
            return $personId;
        } else {
            $root = $this->getParentId($parentId);
            if ($root === null) {
                return $parentId;
            } else {
                return $root;
            }
        }
    }

    private function getPersonById(int $id, int $personTarget): PersonModel
    {
        return $this->getPersonByEloquent(
            $personTarget,
            $this->getPersonEloquentById($id)
        );
    }

    private function getPersonEloquentById(int $id): PeopleEloquentModel
    {
        return PeopleEloquentModel::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->where('id', $id)
            ->first();
    }

    private function getPersonByEloquent(int $personTarget, PeopleEloquentModel $person): PersonModel
    {
        return new PersonModel(
            $person->id,
            $person->surname,
            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy('order')->pluck('surname') : null,
            $person->name,
            $person->patronymic,
            Date::decode($person->birth_date),
            Date::decode($person->death_date),
            ($personTarget === $person->id) ? true : false
        );
    }

    private function getFamily(int $id, int $personTarget): FamilyModel
    {
        $person = $this->getPersonEloquentById($id);

        return new FamilyModel(
            $this->getPersonByEloquent($personTarget, $person),
            $this->getMarriage($id, $personTarget),
            $this->getChildrens($person, $personTarget)
        );
    }

    /**
     * @return Collection<int, PersonModel>
     */
    private function getMarriage(int $personId, int $personTarget): Collection
    {
        $array = [];

        $collection1 = MarriageEloquentModel::where('person1_id', $personId)->pluck('person2_id');
        $collection1->each(
            function ($item) use (&$array, $personTarget) {
                $array[] = $this->getPersonById($item, $personTarget);
            }
        );

        $collection2 = MarriageEloquentModel::where('person2_id', $personId)->pluck('person1_id');
        $collection2->each(
            function ($item) use (&$array, $personTarget) {
                $array[] = $this->getPersonById($item, $personTarget);
            }
        );

        return collect($array);
    }

    /**
     * @return Collection<int, FamilyModel>
     */
    private function getChildrens(PeopleEloquentModel $person, int $personTarget): Collection
    {
        $array = [];

        $childrensId = $person->childrens()->pluck('child_id');
        foreach ($childrensId as $id) {
            $array[] = $this->getFamily($id, $personTarget);
        }

        return collect($array);
    }

    private function getParentId(int $id): ?int
    {
        $parents = PeopleEloquentModel::find($id)->parents()->get();
        if ($parents->isEmpty()) {
            return null;
        } else {
            return $parents[0]->parent_id;
        }
    }

    /**
     * @return Collection<int, PersonShortModel>
     */
    private function getParentsToggle(int $id): Collection
    {
        $array = [];
        $parents = PeopleEloquentModel::select('id', 'surname', 'name', 'patronymic')
            ->find($id)
            ->parentsPerson()
            ->get();
        foreach ($parents as $parent) {
            $array[] = new PersonShortModel(
                $parent->id,
                $parent->surname,
                $parent->name,
                $parent->patronymic
            );
        }

        return collect($array);
    }

    private function getPersonShort(int $id): PersonShortModel
    {
        $person = PeopleEloquentModel::select('id', 'surname', 'name', 'patronymic')->find($id);

        return new PersonShortModel(
            $person->id,
            $person->surname,
            $person->name,
            $person->patronymic
        );
    }
}
