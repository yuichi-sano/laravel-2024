<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     *  @param Role $model
     *
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
