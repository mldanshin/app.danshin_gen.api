<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $person_id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 */
final class Email extends Model
{
    use HasFactory;
}
