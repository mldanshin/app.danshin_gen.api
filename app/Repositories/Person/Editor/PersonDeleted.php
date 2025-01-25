<?php

namespace App\Repositories\Person\Editor;

use App\Exceptions\DataNotFoundException;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Support\Facades\DB;

final class PersonDeleted
{
    public function delete(int $id): void
    {
        $person = PeopleEloquentModel::find($id);
        $photo = new Photo;

        if ($person === null) {
            throw new DataNotFoundException('Requested person does not exist.');
        }

        DB::transaction(
            function () use ($id) {
                $person = PeopleEloquentModel::where('id', $id);
                $person->delete();
            }
        );

        $photo->delete($id);
    }
}
