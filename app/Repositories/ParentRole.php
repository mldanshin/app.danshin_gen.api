<?php

namespace App\Repositories;

use App\Models\Eloquent\ParentRole as ParentRoleEloquent;
use Illuminate\Support\Collection;

final class ParentRole
{
    /**
     * @return Collection<int, string>
     */
    public function getAll(): Collection
    {
        $collect = collect();
        ParentRoleEloquent::get()->map(fn ($item) => $collect->put($item->id, $item->slug));

        return $collect;
    }
}
