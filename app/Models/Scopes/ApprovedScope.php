<?php

namespace App\Models\Scopes;

use App\Models\Torrent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApprovedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder<Torrent> $builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('status', '=', Torrent::APPROVED);
    }
}
