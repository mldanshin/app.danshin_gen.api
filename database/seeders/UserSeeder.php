<?php

namespace Database\Seeders;

use App\Models\Eloquent\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 'mail@example1.com', 'password1', 'user');
        $this->create(2, 'mail@example2.com', 'password2', 'user');
        $this->create(3, 'mail@example3.com', 'password3', 'user');
        $this->create(4, 'a', '9', 'admin');
        $this->create(5, 'u', '9', 'user');
    }

    private function create(int $id, string $email, string $password, string $role): void
    {
        $object = new User;
        $object->id = $id;
        $object->email = $email;
        $object->password = Hash::make($password);
        $object->role = $role;
        $object->save();
    }
}
