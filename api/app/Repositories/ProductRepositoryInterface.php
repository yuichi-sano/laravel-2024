<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function all([array $columns = ['*']]);
    public function create(array $data);
}