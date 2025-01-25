<?php

namespace App\Backup\Photo;

use App\Mail\Backup\Photo\YandexNoticeError as YandexNoticeErrorMail;
use App\Mail\Backup\Photo\YandexNoticeSuccess as YandexNoticeSuccessMail;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Mail;

final class SenderYandex implements SenderContract
{
    public function send(string $path): bool
    {
        $client = new Client;

        // запрос URL для загрузки файла
        $uri = 'https://cloud-api.yandex.net/v1/disk/resources/upload'
            .'?path=%2F%D0%9C%D0%BE%D0%B8+%D0%B4%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D1%8B%2Fprojects%2Fapp%2Fdanshin_gen%2Fapi%2Fbackup%2Fphoto.zip'
            .'&overwrite=true';

        try {
            // запрашиваем ссылку для загрузки файла
            $res = $client->request('GET', $uri, [
                'headers' => [
                    'Authorization' => 'OAuth '.config('backup.yandex_api_token'),
                ],
            ]);

            // получили ссылку для загрузки файла
            $obj = json_decode($res->getBody());
            $href = $obj->href;

            // отправляем файл на Яндекс диск
            $body = Psr7\Utils::tryFopen($path, 'r');
            $r = $client->request('PUT', $href, ['body' => $body]);

            // отправляем уведомление на почту об успешном бэкапе
            Mail::to(config('mail.recipient.address'))
                ->send(new YandexNoticeSuccessMail);

            return true;
        } catch (ClientException $e) {
            Mail::to(config('mail.recipient.address'))
                ->send(new YandexNoticeErrorMail($e->getMessage()));

            return false;
        }
    }
}
