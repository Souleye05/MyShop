<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasPhoneFilter
{
    //
      /**
     * Applique un filtre global par téléphone si le champ 'telephone' est présent dans la requête.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    public static function bootHasPhoneFilter()
    {
        static::addGlobalScope('telephone', function (Builder $builder) {
            if (request()->has('telephone')) {
                $builder->where('telephone', request()->input('telephone'));
            }
        });
    }
}
