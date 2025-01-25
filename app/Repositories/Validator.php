<?php

namespace App\Repositories;

use App\Exceptions\DataNotFoundException;
use App\Models\Eloquent\People as PeopleEloquentModel;

final class Validator
{
    /**
     * @throws DataNotFoundException
     */
    public static function checkPerson(int $id): void
    {
        $person = PeopleEloquentModel::find($id);

        if ($person === null) {
            throw new DataNotFoundException('Requested person does not exist.');
        }
    }

    /**
     * @throws DataNotFoundException
     */
    public static function checkParent(int $personId, ?int $parentId): void
    {
        if ($parentId !== null) {
            $parents = PeopleEloquentModel::find($personId)?->parents()->get();
            if ($parents === null) {
                throw new DataNotFoundException('Requested person does not have a parent.');
            }

            if ($parents->isEmpty()) {
                throw new DataNotFoundException('Requested person does not have a parent.');
            } else {
                $res = $parents->where('parent_id', $parentId);
                if ($res->isEmpty()) {
                    throw new DataNotFoundException("Requested person does not have a parent with id $parentId.");
                }
            }
        }
    }
}
