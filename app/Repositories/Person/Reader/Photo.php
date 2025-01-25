<?php

namespace App\Repositories\Person\Reader;

use App\Exceptions\DataNotFoundException;
use App\Models\Date;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Reader\Photo as PhotoModel;
use App\Services\Photo\FileSystem;
use Illuminate\Support\Collection;

final class Photo
{
    private readonly FileSystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = FileSystem::instance();
    }

    /**
     * @throws DataNotFoundException
     */
    public function getPath(int $personId, string $fileName): string
    {
        if (! PeopleEloquentModel::exists($personId)) {
            throw new DataNotFoundException('Requested person does not exist.');
        }

        $path = $this->fileSystem->getPath($personId, $fileName);
        try {
            $this->fileSystem->existsFile($path);
        } catch (\Exception) {
            throw new DataNotFoundException('Requested file does not exist.');
        }

        return $path;
    }

    /**
     * @return Collection<int, PhotoModel>|null
     */
    public function getListByPerson(int $id): ?Collection
    {
        $person = PeopleEloquentModel::find($id);

        if ($person === null) {
            throw new DataNotFoundException('Requested person does not exist.');
        }

        $collect = $person->photo()->orderBy('order')->get()
            ->map(
                function ($item) {
                    return new PhotoModel(
                        $item->order,
                        $item->file,
                        Date::decode($item->date)
                    );
                }
            );

        if ($collect->count() > 0) {
            return $collect;
        } else {
            return null;
        }
    }
}
