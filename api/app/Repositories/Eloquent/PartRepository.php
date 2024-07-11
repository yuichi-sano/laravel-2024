<?php

namespace App\Repositories\Eloquent;

use App\Models\Part;
use App\Repositories\PartRepositoryInterface;
use Illuminate\pagination\LengthAwarePaginator;

class PartRepository implements PartRepositoryInterface
{
    protected $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    public function all(array $columns)
    {
        return $this->part->all($columns);
    }

    public function create(array $data)
    {
        return $this->part->create($data);
    }

    public function update(array $whereClause, array $data)
    {
        return $this->part->where($whereClause)->update($data);
    }
}