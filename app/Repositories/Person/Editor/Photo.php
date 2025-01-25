<?php

namespace App\Repositories\Person\Editor;

use App\Models\Date as DateModel;
use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Person\Editor\Editable\Photo as EditableModel;
use App\Models\Person\Editor\Stored\Photo as StoredModel;
use App\Models\Person\Editor\Updated\Photo as UpdatedModel;
use App\Services\Photo\FileSystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class Photo
{
    private readonly FileSystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = FileSystem::instance();
    }

    /**
     * @return Collection<int, EditableModel>
     */
    public function getByPerson(int $personId): Collection
    {
        return PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get()
            ->map(
                function ($item) use ($personId) {
                    $path = $this->fileSystem->getPath($personId, $item->file);
                    $this->fileSystem->existsFile($path);

                    return new EditableModel(
                        $item->order,
                        $item->file,
                        DateModel::decode($item->date),
                    );
                }
            );
    }

    /**
     * @param  Collection<int, StoredModel>  $model
     */
    public function store(int $personId, Collection $model): void
    {
        $this->fileSystem->createPerson($personId);

        $this->fileSystem->moveTemp($personId, $model->map(
            fn ($item) => $item->fileName
        ));

        foreach ($model as $item) {
            $fileName = Str::uuid().'.webp';

            $this->convertJpgToWebp(
                $this->fileSystem->getPath($personId, $item->fileName),
                $this->fileSystem->getPath($personId, $fileName),
            );

            $this->storeDB($personId, $item->order, $item->date, $fileName);
        }
    }

    public function storeTemp(UploadedFile $file): string
    {
        return $this->fileSystem->putTemp($file);
    }

    /**
     * @param  Collection<int, UpdatedModel>  $model
     */
    public function update(int $personId, Collection $model): void
    {
        $this->fileSystem->deletePerson($personId);
        PhotoEloquentModel::where('person_id', $personId)->delete();

        $this->fileSystem->createPerson($personId);

        $this->fileSystem->moveTemp($personId, $model->map(
            fn ($item) => $item->fileName
        ));

        foreach ($model as $item) {
            $fileName = Str::uuid().'.webp';

            $this->convertJpgToWebp(
                $this->fileSystem->getPath($personId, $item->fileName),
                $this->fileSystem->getPath($personId, $fileName),
            );

            $this->storeDB($personId, $item->order, $item->date, $fileName);
        }
    }

    public function delete(int $personId): void
    {
        $this->fileSystem->deletePerson($personId);
    }

    public function existsTemp(string $fileName): bool
    {
        if (File::exists($this->fileSystem->getPathTemp($fileName))) {
            return true;
        } else {
            return false;
        }
    }

    private function storeDB(
        int $personId,
        int $order,
        ?DateModel $date,
        string $fileName
    ): void {
        $model = new PhotoEloquentModel;
        $model->person_id = $personId;
        $model->order = $order;
        $model->date = $date?->string;
        $model->file = $fileName;
        $model->save();
    }

    private function convertJpgToWebp(string $pathJpg, string $pathWebp): void
    {
        $imagick = new \Imagick($pathJpg);
        $imagick->setImageFormat('WEBP');

        $imagick->setImageCompressionQuality(80);

        $imagick->writeImage($pathWebp);

        $imagick->clear();
        $imagick->destroy();

        File::delete($pathJpg);
    }
}
