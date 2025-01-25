<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $person1_id
 * @property int $person2_id
 * @property int $role_scope_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
final class Marriage extends Model
{
    use HasFactory;
}
