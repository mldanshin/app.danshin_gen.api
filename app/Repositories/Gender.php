<?php

namespace App\Repositories;

use App\Models\Eloquent\Gender as GenderEloquent;
use Illuminate\Support\Collection;

final class Gender
{
    /**
     * @return Collection<int, string>
     */
    public function getAll(): Collection
    {
        $collect = collect();
        GenderEloquent::get()->map(fn ($item) => $collect->put($item->id, $item->slug));

        return $collect;
    }
}
