<?php

namespace App\Repositories\Person\Editor;

use App\Models\Eloquent\Activity as ActivityEloquentModel;
use App\Models\Eloquent\Email as EmailEloquentModel;
use App\Models\Eloquent\Internet as InternetEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\OldSurname as OldSurnameEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\Phone as PhoneEloquentModel;
use App\Models\Eloquent\Residence as ResidenceEloquentModel;
use App\Models\Person\Editor\Stored\Marriage as MarriageModel;
use App\Models\Person\Editor\Stored\Person as PersonModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class PersonStored
{
    public function __construct(private Photo $photoRepository) {}

    public function store(PersonModel $model): int
    {
        $eloquentModel = new PeopleEloquentModel;

        $personId = DB::transaction(
            function () use ($eloquentModel, $model) {
                $eloquentModel->is_unavailable = $model->isUnavailable;
                $eloquentModel->gender_id = $model->gender;
                $eloquentModel->surname = empty($model->surname) ? null : $model->surname;
                $eloquentModel->name = empty($model->name) ? null : $model->name;
                $eloquentModel->patronymic = ($model->hasPatronymic === true) ? $model->patronymic : '';
                $eloquentModel->birth_date = $model->birthDate?->string;
                $eloquentModel->birth_place = $model->birthPlace;
                $eloquentModel->death_date = ($model->isLive === true) ? null : $model->deathDate?->string;
                $eloquentModel->burial_place = $model->burialPlace;
                $eloquentModel->note = $model->note;
                $eloquentModel->save();

                $this->saveItem(
                    $eloquentModel->activities(),
                    $model->activities,
                    function ($item) {
                        $obj = new ActivityEloquentModel;
                        $obj->name = $item;

                        return $obj;
                    }
                );
                $this->saveItem(
                    $eloquentModel->emails(),
                    $model->emails,
                    function ($item) {
                        $obj = new EmailEloquentModel;
                        $obj->name = $item;

                        return $obj;
                    }
                );
                $this->saveItem(
                    $eloquentModel->internet(),
                    $model->internet,
                    function ($item) {
                        $obj = new InternetEloquentModel;
                        $obj->name = $item->name;
                        $obj->url = $item->url;

                        return $obj;
                    }
                );
                $this->saveItem(
                    $eloquentModel->oldSurname(),
                    $model->oldSurname,
                    function ($item) {
                        $obj = new OldSurnameEloquentModel;
                        $obj->surname = $item->surname;
                        $obj->order = $item->order;

                        return $obj;
                    }
                );
                $this->saveItem(
                    $eloquentModel->phones(),
                    $model->phones,
                    function ($item) {
                        $obj = new PhoneEloquentModel;
                        $obj->name = $item;

                        return $obj;
                    }
                );
                $this->saveItem(
                    $eloquentModel->residences(),
                    $model->residences,
                    function ($item) {
                        $obj = new ResidenceEloquentModel;
                        $obj->name = $item->name;
                        $obj->date = $item->date?->string;

                        return $obj;
                    }
                );
                $this->saveItem(
                    $eloquentModel->parents(),
                    $model->parents,
                    function ($item) {
                        $obj = new ParentChildEloquentModel;
                        $obj->parent_id = $item->person;
                        $obj->parent_role_id = $item->role;

                        return $obj;
                    }
                );
                $this->saveMarriage($eloquentModel->id, $model->marriages);

                $this->photoRepository->store(
                    $eloquentModel->id,
                    $model->photo
                );

                return $eloquentModel->id;
            }
        );

        return $personId;
    }

    private function saveItem(HasMany $roleScope, \Traversable $iterator, callable $func): void
    {
        $roleScope->delete();

        foreach ($iterator as $item) {
            $roleScope->save($func($item));
        }
    }

    /**
     * @param  Collection<int, MarriageModel>  $collection
     */
    private function saveMarriage(int $personId, Collection $collection): void
    {
        MarriageEloquentModel::where('person1_id', $personId)->delete();
        MarriageEloquentModel::where('person2_id', $personId)->delete();

        if ($collection !== null) {
            foreach ($collection as $item) {
                $roleScope = MarriageRoleScopeEloquentModel::where([
                    'role1_id' => $item->role,
                    'role2_id' => $item->soulmateRole,
                ])->value('id');

                $obj = new MarriageEloquentModel;
                $obj->person1_id = $personId;
                $obj->person2_id = $item->soulmate;
                $obj->role_scope_id = $roleScope;
                $obj->save();
            }
        }
    }
}
