<?php

namespace App\Repositories\Person\Editor;

use App\Models\Person\Editor\Created\Person as PersonModel;

final class PersonCreated
{
    public function get(): PersonModel
    {
        return new PersonModel(
            false,
            true,
            1,
            '',
            collect(),
            '',
            '',
            null,
            '',
            null,
            '',
            '',
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            true
        );
    }
}
