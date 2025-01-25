<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $parent_id
 * @property int $child_id
 * @property int $parent_role_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
final class ParentChild extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'parent_child';
}
