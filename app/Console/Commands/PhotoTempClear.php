<?php

namespace App\Console\Commands;

use App\Support\PersonPhotoRepository;
use Illuminate\Console\Command;

final class PhotoTempClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photo:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the temporary photo directory';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(PersonPhotoRepository $support): int
    {
        $res = $support->clearTempDir();
        if ($res === true) {
            $this->info('The command photo:clear was successful!');

            return 0;
        } else {
            $this->error('the command failed with an error');

            return 1;
        }
    }
}
