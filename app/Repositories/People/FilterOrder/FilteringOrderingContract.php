<?php

namespace App\Repositories\People\FilterOrder;

use App\Models\Eloquent\People as PeopleEloquent;
use Illuminate\Support\Collection;

interface FilteringOrderingContract
{
    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function select(Search $search, ?string $text): Collection;
}
