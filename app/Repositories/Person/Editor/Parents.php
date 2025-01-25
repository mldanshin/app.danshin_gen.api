<?php

namespace App\Repositories\Person\Editor;

use App\Models\Date as DateModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\ParentRoleGender as ParentRoleGenderEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Editor\PersonShort as PersonShortModel;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

final readonly class Parents
{
    /**
     * @param  Collection<int, int>  $mariages
     * @return Collection<int, PersonShortModel>
     */
    public function getPossiblePersonShort(
        ?int $personId,
        ?DateModel $birthDate,
        int $role,
        Collection $mariages,
        PersonShort $personShortRepository
    ): Collection {
        $query = PeopleEloquentModel::select('id', 'surname', 'name', 'patronymic', 'birth_date');

        return $this->buildQueryWhere($personId, $birthDate, $role, $mariages, $query)
            ->orderBy('surname')
            ->orderBy('name')
            ->orderBy('patronymic')
            ->get()
            ->map(fn ($item) => $personShortRepository->getPerson($item));
    }

    /**
     * @param  Collection<int, int>  $mariages
     * @return Collection<int, int>
     */
    public function getPossible(
        ?int $personId,
        ?DateModel $birthDate,
        int $role,
        Collection $mariages,
    ): Collection {
        $query = PeopleEloquentModel::select('id');

        return $this->buildQueryWhere($personId, $birthDate, $role, $mariages, $query)
            ->pluck('id');
    }

    /**
     * @param  Collection<int, int>  $mariages
     */
    private function buildQueryWhere(
        ?int $personId,
        ?DateModel $birthDate,
        int $role,
        Collection $mariages,
        EloquentBuilder $query
    ): EloquentBuilder {

        if ($personId !== null) {
            $query = $query->where('id', '<>', $personId);
        }

        if ($birthDate !== null && ! $birthDate->hasUnknown) {
            $query = $query->where(function (EloquentBuilder $query) use ($birthDate) {
                return $query->where('birth_date', '<', $birthDate->string)
                    ->orWhere('birth_date', null)
                    ->orWhere('birth_date', '')
                    ->orWhere('birth_date', 'like', '%?%');
            });
        }

        if ($personId !== null) {
            $query = $query->whereNotIn(
                'id',
                ParentChildEloquentModel::where('parent_id', $personId)->pluck('child_id')->all()
            );
        }

        $query = $query->whereNotIn('id', $mariages->all());

        return $query
            ->whereIn('gender_id', ParentRoleGenderEloquentModel::where('parent_id', $role)->pluck('gender_id'));
    }
}
