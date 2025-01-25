<?php

namespace App\Repositories\Person\Editor;

use App\Exceptions\DataNotFoundException;
use App\Models\Date as DateModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Editor\Editable\Internet as InternetModel;
use App\Models\Person\Editor\Editable\Marriage as MarriageModel;
use App\Models\Person\Editor\Editable\OldSurname as OldSurnameModel;
use App\Models\Person\Editor\Editable\ParentModel;
use App\Models\Person\Editor\Editable\Person as PersonModel;
use App\Models\Person\Editor\Editable\Residence as ResidenceModel;
use Illuminate\Support\Collection;

final class PersonEditable
{
    public function getById(int $id): PersonModel
    {
        $person = PeopleEloquentModel::find($id);
        $photo = new Photo;

        if ($person === null) {
            throw new DataNotFoundException('Requested person does not exist.');
        }

        return new PersonModel(
            $person->id,
            $person->is_unavailable,
            $this->getLive($person->death_date),
            $person->gender_id,
            $person->surname,
            $this->getOldSurname($person),
            $person->name,
            $person->patronymic,
            DateModel::decode($person->birth_date),
            $person->birth_place,
            DateModel::decode($person->death_date),
            $person->burial_place,
            $person->note,
            $person->activities()->pluck('name'),
            $person->emails()->pluck('name'),
            $this->getInternet($person),
            $person->phones()->pluck('name'),
            $this->getResidences($person),
            $this->getParents($person),
            $this->getMarriages($person),
            $photo->getByPerson($person->id),
            ($person->patronymic === '') ? false : true
        );
    }

    private function getLive(?string $deathDate): bool
    {
        if ($deathDate === null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Collection<int, OldSurnameModel>
     */
    private function getOldSurname(PeopleEloquentModel $person): Collection
    {
        $array = [];
        $collection = $person->oldSurname()->orderBy('order')->get();
        foreach ($collection as $item) {
            $array[] = new OldSurnameModel($item->surname, $item->order);
        }

        return collect($array);
    }

    /**
     * @return Collection<int, InternetModel>
     */
    private function getInternet(PeopleEloquentModel $person): Collection
    {
        $array = [];
        $collection = $person->internet()->get();
        foreach ($collection as $item) {
            $array[] = new InternetModel($item->url, $item->name);
        }

        return collect($array);
    }

    /**
     * @return Collection<int, ResidenceModel>
     */
    private function getResidences(PeopleEloquentModel $person): Collection
    {
        $array = [];
        $collection = $person->residences()->get();
        foreach ($collection as $item) {
            $array[] = new ResidenceModel(
                $item->name,
                DateModel::decode($item->date)
            );
        }

        return collect($array);
    }

    /**
     * @return Collection<int, ParentModel>
     */
    private function getParents(PeopleEloquentModel $person): Collection
    {
        $array = [];

        $collection = $person->parents()->get();

        foreach ($collection as $item) {
            $array[] = new ParentModel(
                $item->parent_id,
                $item->parent_role_id
            );
        }

        return collect($array);
    }

    /**
     * @return Collection<int, MarriageModel>
     */
    private function getMarriages(PeopleEloquentModel $person): Collection
    {
        $array = [];

        $roleScope = MarriageRoleScopeEloquentModel::get();

        $collection1 = MarriageEloquentModel::where('person1_id', $person->id)->get();
        $collection1->each(
            function ($item) use (&$array, $roleScope) {
                $scope = $roleScope->find($item->role_scope_id);
                $array[] = new MarriageModel(
                    $scope->role1_id,
                    $item->person2_id,
                    $scope->role2_id
                );
            }
        );

        $collection2 = MarriageEloquentModel::where('person2_id', $person->id)->get();
        $collection2->each(
            function ($item) use (&$array, $roleScope) {
                $scope = $roleScope->find($item->role_scope_id);
                $array[] = new MarriageModel(
                    $scope->role2_id,
                    $item->person1_id,
                    $scope->role1_id
                );
            }
        );

        return collect($array);
    }
}
