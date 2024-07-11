<?php

namespace App\Repositories\Eloquent;

use App\Models\Total;
use App\Repositories\TotalRepositoryInterface;
use Illuminate\pagination\LengthAwarePaginator;

class TotalRepository implements TotalRepositoryInterface
{
    protected $total;

    public function _construct(Total $total)
    {
        $this->total = $total;
    }

    public function create(array $data)
    {
        return $this->total->create($data);
    }

    public function update(array $whereClause, array $data)
    {
        return $this->total->where($whereClause)->update($data);
    }

    public function all(array $columns)
    {
        return $this->total->all($columns);
    }
}