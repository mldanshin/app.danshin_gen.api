<?php

namespace App\Repositories\People\FilterOrder;

use App\Models\Eloquent\People as PeopleEloquent;
use Illuminate\Support\Collection;

final class Name implements FilteringOrderingContract
{
    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function select(Search $search, ?string $text): Collection
    {
        $builder = PeopleEloquent::selectRaw(
            'id, surname, name, patronymic, '
            .'(CASE WHEN surname IS NULL THEN 1 ELSE 0 END) as isSurnameNull, '
            .'(CASE WHEN name IS NULL THEN 1 ELSE 0 END) as isNameNull, '
            .'(CASE WHEN patronymic IS NULL THEN 1 ELSE 0 END) as isPatronymicNull'
        );
        $builder = $search->getBuilder($text, $builder);

        return $builder->orderByRaw('isSurnameNull, isNameNull, isPatronymicNull, surname, name, patronymic  asc')
            ->get();
    }
}
