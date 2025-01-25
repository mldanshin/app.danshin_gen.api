<?php

namespace App\Repositories;

use App\Exceptions\DataNotFoundException;
use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\MarriageRole as MarriageRoleEloquent;
use Illuminate\Support\Collection;

final class MarriageRole
{
    /**
     * @return Collection<int, string>
     */
    public function getAll(): Collection
    {
        $collect = collect();
        MarriageRoleEloquent::get()->map(fn ($item) => $collect->put($item->id, $item->slug));

        return $collect;
    }

    /**
     * @return Collection<int, string>
     */
    public function getByGender(int $gender): Collection
    {
        $obj = GenderEloquentModel::find($gender);
        if ($obj === null) {
            throw new DataNotFoundException('Requested gender does not exist.');
        }

        $collect = collect();
        $obj->marriages()->orderBy('id')->get()
            ->map(
                fn ($item) => $collect->put($item->id, $item->slug)
            );

        return $collect;
    }
}
