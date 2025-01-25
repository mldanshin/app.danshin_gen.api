<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokensSeeder extends Seeder
{
    public function run(): void
    {
        $this->create("App\Models\Eloquent\User", 4, 'admintoken', '4pawb2A0kxKSQuUAMtbCjH6n3CBbAj8snUnFU0Zs', ['admin', 'user']);
        $this->create("App\Models\Eloquent\User", 5, 'usertoken', 'GQNzZM0tHNTPtaxEv56832MnCAXtmHzt7kNPtgh8', ['user']);
    }

    private function create(
        string $tokenable_type,
        int $tokenable_id,
        string $name,
        string $token,
        array $abilities
    ): void {
        $object = new PersonalAccessToken;
        $object->tokenable_type = $tokenable_type;
        $object->tokenable_id = $tokenable_id;
        $object->name = $name;
        $object->token = hash('sha256', $token);
        $object->abilities = $abilities;
        $object->expires_at = null;
        $object->last_used_at = null;
        $object->save();
    }
}
