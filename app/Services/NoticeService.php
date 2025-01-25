<?php

namespace App\Services;

use App\Exceptions\ServerException;
use App\Models\Dates\DateType;
use Illuminate\Support\Facades\Http;

final class NoticeService
{
    private string $apiKey;
    private string $urlCreated;
    private string $urlDeleted;

    public function __construct()
    {
        $this->apiKey = config('services.notice_api_key');
        $this->urlCreated = config('services.notice_url_created');
        $this->urlDeleted = config('services.notice_url_deleted');
    }

    public function createPerson(int $id, DateType $dateType): bool
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->urlCreated, [
                'personId' => $id,
                'type' => $dateType->getStringValue(),
            ]);

            if ($response->status() == 201) {
                return true;
            }
        } catch (ServerException) {
            return false;
        }
        
        return false;
    }

    public function deletePerson(int $id): bool
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->urlDeleted, [
                'personId' => $id
            ]);

            if ($response->status() == 204) {
                return true;
            }
        } catch (ServerException) {
            return false;
        }
        
        return false;
    }
}