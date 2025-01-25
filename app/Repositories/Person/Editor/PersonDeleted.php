<?php

namespace App\Repositories\Person\Editor;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\ServerException;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Services\NoticeService;
use Illuminate\Support\Facades\DB;

final class PersonDeleted
{
    public function __construct(
        private NoticeService $noticeService
    ) {}

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

                if (!$this->noticeService->deletePerson($id)) {
                    throw new ServerException('Failed to delete notice for person: ' . $id);
                }
            }
        );

        $photo->delete($id);
    }
}
