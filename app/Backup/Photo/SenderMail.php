<?php

namespace App\Backup\Photo;

use App\Mail\Backup\Photo\Photo as PhotoMail;
use Illuminate\Support\Facades\Mail;

final class SenderMail implements SenderContract
{
    public function send(string $path): bool
    {
        Mail::to(config('mail.recipient.address'))->send(new PhotoMail($path));

        return true;
    }
}
