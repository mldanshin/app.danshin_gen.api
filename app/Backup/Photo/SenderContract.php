<?php

namespace App\Backup\Photo;

interface SenderContract
{
    public function send(string $path): bool;
}
