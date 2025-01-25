<?php

namespace App\Console\Commands;

use App\Backup\Photo\SenderYandex as Sender;
use App\Backup\Photo\Storage;
use Illuminate\Console\Command;

class BackupPhotoSender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:photo-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a photo archive and sends';

    /**
     * Execute the console command.
     */
    public function handle(Sender $sender)
    {
        $storage = new Storage;
        $storage->createFile();

        if ($sender->send($storage->path) === true) {
            $this->info('Photo archive successfully sent.');

            return 0;
        } else {
            $this->error('Photo archive sent error');

            return 1;
        }
    }
}
