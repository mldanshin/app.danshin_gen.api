<?php

namespace App\Console\Commands;

use App\Backup\Photo\Storage;
use Illuminate\Console\Command;

class BackupPhotoCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:photo-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creation of a photo archive and placement in storage';

    /**
     * Execute the console command.
     */
    public function handle(Storage $storage)
    {
        $storage->createFile();

        $this->info(
            'The photo archive file has been successfully created and placed in the storage.'
        );

        return 0;
    }
}
