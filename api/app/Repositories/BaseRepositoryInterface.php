<?php

namespace App\Repositories;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $columns);
    public function findById($id, array $columns);
    public function findWhere($conditions);
    public function create(array $data);
    public function insertGetId(array $data);
    public function update(array $whereClause, array $data);
    public function updateById(array $data, int $id);
    public function updateWhere(array $condition, array $data);
    public function deleteById(int $id);
    public function deleteWhere(array $condition);
    public function updateOrCreate(array $uniqueData, array $normalData);
}
