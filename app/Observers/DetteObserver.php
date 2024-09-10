<?php

namespace App\Observers;

use App\Models\Dette;
use App\Models\User;


class DetteObserver
{
    //

    public function creating(Dette $dette)
    {
        $dette->montant = $dette->montant * 100;
    }
}
