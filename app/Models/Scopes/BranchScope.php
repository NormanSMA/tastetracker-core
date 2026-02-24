<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * BranchScope — Multi-tenancy estricto (Blueprint 1.2).
 *
 * Filtra automáticamente registros por el branch_id del usuario autenticado.
 * Solo se aplica cuando hay un usuario con branch_id asignado.
 */
class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if ($user && $user->branch_id) {
            $builder->where($model->getTable().'.branch_id', $user->branch_id);
        }
    }
}
