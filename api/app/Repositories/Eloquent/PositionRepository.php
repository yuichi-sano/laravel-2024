<?php

namespace App\Repositories\Eloquent;

use App\Models\Position;
use App\Repositories\PositionRepositoryInterface;
use Illuminate\pagination\LengthAwarePaginator;

class PositionRepository implements PositionRepositoryInterface
{
    protected $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    public function all(array $columns)
    {
        return $this->position->all($columns);
    }

    public function create(array $data)
    {
        return $this->position->create($data);
    }

    public function update(array $whereClause, array $data)
    {
        return $this->position->where($whereClause)->update($data);
    }
}