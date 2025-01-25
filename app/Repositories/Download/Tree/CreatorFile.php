<?php

namespace App\Repositories\Download\Tree;

use App\Exceptions\DataNotFoundException;
use App\Repositories\Download\CreatorFile as CreatorFileBase;
use App\Repositories\Tree\Tree as TreeRepository;
use App\View\Tree\Tree as TreeView;
use Illuminate\Support\Facades\File;

final class CreatorFile extends CreatorFileBase
{
    public function __construct(
        private readonly int $personId,
        private readonly ?int $parentId
    ) {}

    /**
     * @throws \Exception
     */
    public function create(string $pathDirectory): string
    {
        $treeModel = (new TreeRepository)->get($this->personId, $this->parentId);
        if ($treeModel === null) {
            throw new DataNotFoundException(
                "The requested person {$this->personId} does not exist with the parent {$this->parentId}"
            );
        }

        $content = (new TreeView($treeModel, null))->content;

        $pathFile = $this->generateFilePath($pathDirectory);
        try {
            if (File::put($pathFile, $content) !== false) {
                return $pathFile;
            } else {
                throw new \Exception("Failed to write file, id={$this->personId}, parentId={$this->parentId}");
            }
        } catch (\Exception) {
            throw new \Exception("Failed to write file, id={$this->personId}, parentId={$this->parentId}");
        } catch (\Error) {
            throw new \Exception("Failed to write file, id={$this->personId}, parentId={$this->parentId}");
        }
    }

    private function generateFilePath(string $pathDirectory): string
    {
        $pathFile = $pathDirectory."danshin_genealogy_tree_{$this->personId}";
        if ($this->parentId !== null) {
            $pathFile .= "_{$this->parentId}";
        }

        return $pathFile.'.svg';
    }
}
