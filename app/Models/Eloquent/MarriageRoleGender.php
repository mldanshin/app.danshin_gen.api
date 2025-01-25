<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $role_id
 * @property int $gender_id
 */
final class MarriageRoleGender extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'marriage_role_gender';

    /**
     * @var bool
     */
    public $timestamps = false;
}
